<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\KonsultasiAuditLog;
use App\Models\KonsultasiPesan;
use App\Models\Kunjungan;
use App\Models\Rujukan;
use App\Models\RumahSakit;
use App\Models\User;
use App\Notifications\KonsultasiActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class KonsultasiController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureConsultationAccess();

        $user = auth()->user();
        $statuses = Konsultasi::statusLabels();

        $query = Konsultasi::query()
            ->visibleTo($user)
            ->with([
                'kunjungan.pasien',
                'rsAsal',
                'rsTujuan',
                'dokterPengirim',
                'dokterTujuan',
                'latestMessage.pengirim',
            ]);

        if ($keyword = trim($request->input('keyword', ''))) {
            $query->where(function ($inner) use ($keyword) {
                $inner->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('alasan_konsultasi', 'like', "%{$keyword}%")
                    ->orWhereHas('kunjungan', function ($kunjungan) use ($keyword) {
                        $kunjungan->where('no_rawat', 'like', "%{$keyword}%")
                            ->orWhereHas('pasien', function ($pasien) use ($keyword) {
                                $pasien->where('no_rkm_medis', 'like', "%{$keyword}%")
                                    ->orWhere('nama', 'like', "%{$keyword}%");
                            });
                    });
            });
        }

        if ($status = $request->input('status')) {
            if (array_key_exists($status, $statuses)) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('arah')) {
            if ($request->input('arah') === 'masuk') {
                $query->where('dokter_tujuan_id', $user->id);
            }

            if ($request->input('arah') === 'keluar') {
                $query->where('dokter_pengirim_id', $user->id);
            }
        }

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50], true)) {
            $perPage = 10;
        }

        $konsultasi = $query->latest()->paginate($perPage)->withQueryString();

        return view('konsultasi.index', compact('konsultasi', 'statuses'));
    }

    public function create(Request $request)
    {
        $this->ensureConsultationAccess();

        $user = auth()->user();
        $rsAsalId = (int) $user->rumah_sakit_id;
        $selectedKunjungan = null;

        if ($request->filled('kunjungan_id')) {
            $selectedKunjungan = Kunjungan::with(['pasien', 'dokter', 'soap.user'])->findOrFail($request->integer('kunjungan_id'));
            $this->assertKunjunganCanBeConsulted($selectedKunjungan);
        }

        $kunjungan = Kunjungan::with(['pasien', 'dokter'])
            ->where('rumah_sakit_id', $rsAsalId)
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('id')
            ->get();

        $rumahSakitTujuan = RumahSakit::where('id', '!=', $rsAsalId)->orderBy('nama')->get();
        $dokterTujuan = collect();
        if (old('rumah_sakit_tujuan_id')) {
            $dokterTujuan = User::where('role', 'dokter')
                ->where('rumah_sakit_id', old('rumah_sakit_tujuan_id'))
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $latestSoap = $selectedKunjungan?->soap->sortByDesc('created_at')->first();

        return view('konsultasi.create', [
            'kunjungan' => $kunjungan,
            'rumahSakitTujuan' => $rumahSakitTujuan,
            'dokterTujuan' => $dokterTujuan,
            'selectedKunjungan' => $selectedKunjungan,
            'latestSoap' => $latestSoap,
            'consentOptions' => $this->consentOptions(),
            'consentMethods' => $this->consentMethods(),
            'formAction' => route('konsultasi.store'),
            'submitLabel' => 'Simpan Konsultasi',
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureConsultationAccess();

        $isSubmit = $request->input('submit_action') === 'submit';
        $payload = $this->validatedPayload($request, $isSubmit);
        $kunjungan = Kunjungan::with(['pasien', 'soap'])->findOrFail($payload['kunjungan_id']);
        $this->assertKunjunganCanBeConsulted($kunjungan);

        $consultation = DB::transaction(function () use ($payload, $kunjungan, $isSubmit) {
            $consultation = Konsultasi::create([
                'kunjungan_id' => $kunjungan->id,
                'rumah_sakit_asal_id' => $kunjungan->rumah_sakit_id,
                'rumah_sakit_tujuan_id' => $payload['rumah_sakit_tujuan_id'],
                'dokter_pengirim_id' => auth()->id(),
                'dokter_tujuan_id' => $payload['dokter_tujuan_id'],
                'judul' => $payload['judul'],
                'ringkasan_klinis' => $payload['ringkasan_klinis'] ?? null,
                'diagnosis_kerja' => $payload['diagnosis_kerja'] ?? null,
                'terapi_berjalan' => $payload['terapi_berjalan'] ?? null,
                'hasil_penunjang' => $payload['hasil_penunjang'] ?? null,
                'alasan_konsultasi' => $payload['alasan_konsultasi'],
                'pertanyaan_konsultasi' => $payload['pertanyaan_konsultasi'] ?? null,
                'consent_status' => $payload['consent_status'],
                'consent_nama_pemberi' => $payload['consent_nama_pemberi'] ?? null,
                'consent_hubungan' => $payload['consent_hubungan'] ?? null,
                'consent_metode' => $payload['consent_metode'] ?? null,
                'consent_diberikan_pada' => $payload['consent_diberikan_pada'] ?? null,
                'consent_catatan' => $payload['consent_catatan'] ?? null,
                'status' => $isSubmit ? Konsultasi::STATUS_TERKIRIM : Konsultasi::STATUS_DRAFT,
                'submitted_at' => $isSubmit ? now() : null,
            ]);

            $this->audit($consultation, 'dibuat', [
                'status' => $consultation->status,
                'dokter_tujuan_id' => $consultation->dokter_tujuan_id,
            ]);

            if ($isSubmit) {
                $this->audit($consultation, 'dikirim', [
                    'consent_status' => $consultation->consent_status,
                ]);
            }

            return $consultation;
        });

        if ($isSubmit) {
            $this->notifyUser(
                $consultation->dokterTujuan,
                $consultation,
                'konsultasi_baru'
            );
        }

        return redirect()
            ->route('konsultasi.show', $consultation)
            ->with('success', $isSubmit ? 'Konsultasi berhasil dikirim.' : 'Draft konsultasi berhasil disimpan.');
    }

    public function show(Konsultasi $konsultasi)
    {
        $this->ensureConsultationAccess();
        $this->assertViewable($konsultasi);

        $konsultasi->load([
            'kunjungan.pasien',
            'kunjungan.dokter',
            'kunjungan.soap.user',
            'kunjungan.berkasMedis.uploader',
            'rsAsal',
            'rsTujuan',
            'dokterPengirim',
            'dokterTujuan',
            'rujukan',
            'pesan.pengirim',
            'auditLogs.user',
        ]);

        if ($konsultasi->isTarget(auth()->user())
            && $konsultasi->status === Konsultasi::STATUS_TERKIRIM
            && $konsultasi->consent_status === Konsultasi::CONSENT_DIBERIKAN) {
            $konsultasi->markAsRead();
            $this->audit($konsultasi, 'dibaca');
            $konsultasi->refresh();
            $konsultasi->load([
                'kunjungan.pasien',
                'kunjungan.dokter',
                'kunjungan.soap.user',
                'kunjungan.berkasMedis.uploader',
                'rsAsal',
                'rsTujuan',
                'dokterPengirim',
                'dokterTujuan',
                'rujukan',
                'pesan.pengirim',
                'auditLogs.user',
            ]);
        }

        $this->markRelatedNotificationsAsRead($konsultasi, auth()->user());

        $latestSoap = $konsultasi->kunjungan->soap->sortByDesc('created_at')->first();
        $replyTypes = auth()->user()->id === $konsultasi->dokter_tujuan_id
            ? KonsultasiPesan::typeLabels()
            : ['pesan' => KonsultasiPesan::typeLabels()['pesan']];

        return view('konsultasi.show', compact('konsultasi', 'latestSoap', 'replyTypes'));
    }

    public function edit(Konsultasi $konsultasi)
    {
        $this->ensureConsultationAccess();
        $this->assertEditable($konsultasi);

        $user = auth()->user();
        $rsAsalId = (int) $user->rumah_sakit_id;
        $konsultasi->load(['kunjungan.pasien', 'kunjungan.dokter', 'kunjungan.soap.user']);

        $kunjungan = Kunjungan::with(['pasien', 'dokter'])
            ->where('rumah_sakit_id', $rsAsalId)
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('id')
            ->get();

        $rumahSakitTujuan = RumahSakit::where('id', '!=', $rsAsalId)->orderBy('nama')->get();
        $dokterTujuan = User::where('role', 'dokter')
            ->where('rumah_sakit_id', old('rumah_sakit_tujuan_id', $konsultasi->rumah_sakit_tujuan_id))
            ->orderBy('name')
            ->get(['id', 'name']);

        $latestSoap = $konsultasi->kunjungan->soap->sortByDesc('created_at')->first();

        return view('konsultasi.edit', [
            'konsultasi' => $konsultasi,
            'kunjungan' => $kunjungan,
            'rumahSakitTujuan' => $rumahSakitTujuan,
            'dokterTujuan' => $dokterTujuan,
            'selectedKunjungan' => $konsultasi->kunjungan,
            'latestSoap' => $latestSoap,
            'consentOptions' => $this->consentOptions(),
            'consentMethods' => $this->consentMethods(),
            'formAction' => route('konsultasi.update', $konsultasi),
            'submitLabel' => 'Perbarui Konsultasi',
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Konsultasi $konsultasi)
    {
        $this->ensureConsultationAccess();
        $this->assertEditable($konsultasi);

        $isSubmit = $request->input('submit_action') === 'submit';
        $payload = $this->validatedPayload($request, $isSubmit);
        $kunjungan = Kunjungan::findOrFail($payload['kunjungan_id']);
        $this->assertKunjunganCanBeConsulted($kunjungan);

        $shouldNotifyTarget = false;

        DB::transaction(function () use ($konsultasi, $payload, $kunjungan, $isSubmit, &$shouldNotifyTarget) {
            $status = $konsultasi->status;
            $submittedAt = $konsultasi->submitted_at;

            if ($isSubmit) {
                $shouldNotifyTarget = $status === Konsultasi::STATUS_DRAFT;
                $status = Konsultasi::STATUS_TERKIRIM;
                $submittedAt = $submittedAt ?? now();
            }

            $konsultasi->update([
                'kunjungan_id' => $kunjungan->id,
                'rumah_sakit_asal_id' => $kunjungan->rumah_sakit_id,
                'rumah_sakit_tujuan_id' => $payload['rumah_sakit_tujuan_id'],
                'dokter_tujuan_id' => $payload['dokter_tujuan_id'],
                'judul' => $payload['judul'],
                'ringkasan_klinis' => $payload['ringkasan_klinis'] ?? null,
                'diagnosis_kerja' => $payload['diagnosis_kerja'] ?? null,
                'terapi_berjalan' => $payload['terapi_berjalan'] ?? null,
                'hasil_penunjang' => $payload['hasil_penunjang'] ?? null,
                'alasan_konsultasi' => $payload['alasan_konsultasi'],
                'pertanyaan_konsultasi' => $payload['pertanyaan_konsultasi'] ?? null,
                'consent_status' => $payload['consent_status'],
                'consent_nama_pemberi' => $payload['consent_nama_pemberi'] ?? null,
                'consent_hubungan' => $payload['consent_hubungan'] ?? null,
                'consent_metode' => $payload['consent_metode'] ?? null,
                'consent_diberikan_pada' => $payload['consent_diberikan_pada'] ?? null,
                'consent_catatan' => $payload['consent_catatan'] ?? null,
                'status' => $status,
                'submitted_at' => $submittedAt,
            ]);

            $this->audit($konsultasi, 'diperbarui', [
                'status' => $konsultasi->status,
                'dokter_tujuan_id' => $konsultasi->dokter_tujuan_id,
            ]);

            if ($isSubmit && $konsultasi->status === Konsultasi::STATUS_TERKIRIM) {
                $this->audit($konsultasi, 'dikirim', [
                    'consent_status' => $konsultasi->consent_status,
                ]);
            }
        });

        if ($shouldNotifyTarget) {
            $konsultasi->refresh();
            $this->notifyUser(
                $konsultasi->dokterTujuan,
                $konsultasi,
                'konsultasi_baru'
            );
        }

        return redirect()
            ->route('konsultasi.show', $konsultasi)
            ->with('success', $isSubmit ? 'Konsultasi berhasil diperbarui dan dikirim.' : 'Draft konsultasi berhasil diperbarui.');
    }

    public function destroy(Konsultasi $konsultasi)
    {
        $this->ensureConsultationAccess();
        $this->assertEditable($konsultasi);

        DB::transaction(function () use ($konsultasi) {
            $konsultasi->pesan()->delete();
            $konsultasi->auditLogs()->delete();
            $konsultasi->delete();
        });

        return redirect()->route('konsultasi.index')->with('success', 'Draft konsultasi berhasil dihapus.');
    }

    public function accept(Konsultasi $konsultasi)
    {
        $this->ensureConsultationAccess();
        $this->assertTargetActor($konsultasi);

        abort_unless(in_array($konsultasi->status, [
            Konsultasi::STATUS_TERKIRIM,
            Konsultasi::STATUS_DIBACA,
        ], true), 422);

        $konsultasi->update([
            'status' => Konsultasi::STATUS_DITERIMA,
            'accepted_at' => now(),
            'read_at' => $konsultasi->read_at ?? now(),
        ]);

        $this->audit($konsultasi, 'diterima');

        return back()->with('success', 'Konsultasi sudah Anda terima.');
    }

    public function reply(Request $request, Konsultasi $konsultasi)
    {
        $this->ensureConsultationAccess();
        $this->assertViewable($konsultasi);
        abort_unless($konsultasi->canReply(), 422, 'Konsultasi ini sudah tidak dapat dibalas.');

        $allowedTypes = auth()->id() === (int) $konsultasi->dokter_tujuan_id
            ? array_keys(KonsultasiPesan::typeLabels())
            : ['pesan'];

        $payload = $request->validate([
            'tipe' => ['required', Rule::in($allowedTypes)],
            'pesan' => ['required', 'string'],
        ]);

        $recipient = null;

        DB::transaction(function () use ($konsultasi, $payload, &$recipient) {
            $message = $konsultasi->pesan()->create([
                'pengirim_id' => auth()->id(),
                'tipe' => $payload['tipe'],
                'pesan' => $payload['pesan'],
            ]);

            $newStatus = Konsultasi::STATUS_DISKUSI;
            if ($konsultasi->isTarget(auth()->user())) {
                $newStatus = match ($payload['tipe']) {
                    'jawaban' => Konsultasi::STATUS_DIJAWAB,
                    'minta_info' => Konsultasi::STATUS_BUTUH_INFO,
                    default => Konsultasi::STATUS_DISKUSI,
                };
            }

            $konsultasi->update([
                'status' => $newStatus,
                'read_at' => $konsultasi->read_at ?? now(),
            ]);

            $this->audit($konsultasi, 'balas', [
                'tipe' => $message->tipe,
                'pesan_id' => $message->id,
            ]);

            $recipient = $konsultasi->isTarget(auth()->user())
                ? $konsultasi->dokterPengirim
                : $konsultasi->dokterTujuan;
        });

        if ($recipient) {
            $konsultasi->refresh();
            $this->notifyUser(
                $recipient,
                $konsultasi,
                'pesan_baru'
            );
        }

        return back()->with('success', 'Balasan konsultasi berhasil dikirim.');
    }

    public function close(Konsultasi $konsultasi)
    {
        $this->ensureConsultationAccess();
        $this->assertViewable($konsultasi);

        abort_unless($konsultasi->status !== Konsultasi::STATUS_DRAFT, 422);
        abort_unless($konsultasi->status !== Konsultasi::STATUS_DIRUJUK, 422);

        $konsultasi->update([
            'status' => Konsultasi::STATUS_DITUTUP,
            'closed_at' => now(),
            'closed_by' => auth()->id(),
        ]);

        $this->audit($konsultasi, 'ditutup');

        return back()->with('success', 'Konsultasi ditutup.');
    }

    public function escalate(Konsultasi $konsultasi)
    {
        $this->ensureConsultationAccess();
        $this->assertSenderActor($konsultasi);

        abort_unless($konsultasi->status !== Konsultasi::STATUS_DRAFT, 422);
        abort_unless($konsultasi->consent_status === Konsultasi::CONSENT_DIBERIKAN, 422, 'Consent pasien belum lengkap.');

        if ($konsultasi->rujukan_id) {
            return redirect()->route('rujukan.show', $konsultasi->rujukan_id)
                ->with('info', 'Konsultasi ini sudah pernah dijadikan rujukan resmi.');
        }

        $lastClinicalReply = $konsultasi->pesan()
            ->whereIn('tipe', ['jawaban', 'minta_info'])
            ->latest()
            ->first();

        $rujukan = DB::transaction(function () use ($konsultasi, $lastClinicalReply) {
            $rujukan = Rujukan::create([
                'kunjungan_id' => $konsultasi->kunjungan_id,
                'rumah_sakit_asal_id' => $konsultasi->rumah_sakit_asal_id,
                'rumah_sakit_tujuan_id' => $konsultasi->rumah_sakit_tujuan_id,
                'dokter_tujuan_id' => $konsultasi->dokter_tujuan_id,
                'alasan' => $konsultasi->judul,
                'alasan_rujukan' => $konsultasi->alasan_konsultasi,
                'catatan' => trim(implode("\n\n", array_filter([
                    'Pertanyaan konsultasi: '.$konsultasi->pertanyaan_konsultasi,
                    'Ringkasan klinis: '.$konsultasi->ringkasan_klinis,
                    $lastClinicalReply ? 'Tindak lanjut konsultasi: '.$lastClinicalReply->pesan : null,
                ]))),
                'status' => 'menunggu',
            ]);

            $konsultasi->update([
                'rujukan_id' => $rujukan->id,
                'status' => Konsultasi::STATUS_DIRUJUK,
            ]);

            $this->audit($konsultasi, 'dijadikan_rujukan', [
                'rujukan_id' => $rujukan->id,
            ]);

            return $rujukan;
        });

        return redirect()->route('rujukan.show', $rujukan)->with('success', 'Konsultasi berhasil dilanjutkan menjadi rujukan resmi.');
    }

    private function validatedPayload(Request $request, bool $isSubmit): array
    {
        $payload = $request->validate([
            'kunjungan_id' => ['required', Rule::exists('kunjungan', 'id')],
            'rumah_sakit_tujuan_id' => ['required', Rule::exists('rumah_sakit', 'id')],
            'dokter_tujuan_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) use ($request) {
                    $query->where('role', 'dokter')
                        ->where('rumah_sakit_id', $request->input('rumah_sakit_tujuan_id'));
                }),
            ],
            'judul' => ['required', 'string', 'max:255'],
            'ringkasan_klinis' => ['nullable', 'string'],
            'diagnosis_kerja' => ['nullable', 'string'],
            'terapi_berjalan' => ['nullable', 'string'],
            'hasil_penunjang' => ['nullable', 'string'],
            'alasan_konsultasi' => ['required', 'string'],
            'pertanyaan_konsultasi' => ['nullable', 'string'],
            'consent_status' => ['required', Rule::in(array_keys($this->consentOptions()))],
            'consent_nama_pemberi' => ['nullable', 'string', 'max:255'],
            'consent_hubungan' => ['nullable', 'string', 'max:255'],
            'consent_metode' => ['nullable', Rule::in(array_keys($this->consentMethods()))],
            'consent_diberikan_pada' => ['nullable', 'date'],
            'consent_catatan' => ['nullable', 'string'],
        ]);

        $kunjungan = Kunjungan::findOrFail($payload['kunjungan_id']);
        $this->assertKunjunganCanBeConsulted($kunjungan);

        if ((int) $payload['rumah_sakit_tujuan_id'] === (int) $kunjungan->rumah_sakit_id) {
            throw ValidationException::withMessages([
                'rumah_sakit_tujuan_id' => 'Rumah sakit tujuan harus berbeda dari rumah sakit asal.',
            ]);
        }

        if ($payload['consent_status'] === Konsultasi::CONSENT_DIBERIKAN) {
            if (
                !filled($payload['consent_nama_pemberi'] ?? null)
                || !filled($payload['consent_hubungan'] ?? null)
                || !filled($payload['consent_metode'] ?? null)
                || !filled($payload['consent_diberikan_pada'] ?? null)
            ) {
                throw ValidationException::withMessages([
                    'consent_status' => 'Data persetujuan pasien belum lengkap.',
                ]);
            }
        }

        if ($isSubmit) {
            if ($payload['consent_status'] !== Konsultasi::CONSENT_DIBERIKAN) {
                throw ValidationException::withMessages([
                    'consent_status' => 'Consent pasien harus sudah diberikan sebelum konsultasi dikirim.',
                ]);
            }
        }

        return $payload;
    }

    private function assertKunjunganCanBeConsulted(Kunjungan $kunjungan): void
    {
        abort_unless((int) $kunjungan->rumah_sakit_id === (int) auth()->user()->rumah_sakit_id, 403);
    }

    private function assertViewable(Konsultasi $konsultasi): void
    {
        $allowed = Konsultasi::query()
            ->visibleTo(auth()->user())
            ->whereKey($konsultasi->id)
            ->exists();

        abort_unless($allowed, 403);
    }

    private function assertEditable(Konsultasi $konsultasi): void
    {
        abort_unless($konsultasi->isSender(auth()->user()), 403);
        abort_unless($konsultasi->status === Konsultasi::STATUS_DRAFT, 422);
    }

    private function assertTargetActor(Konsultasi $konsultasi): void
    {
        $this->assertViewable($konsultasi);
        abort_unless($konsultasi->isTarget(auth()->user()), 403);
    }

    private function assertSenderActor(Konsultasi $konsultasi): void
    {
        $this->assertViewable($konsultasi);
        abort_unless($konsultasi->isSender(auth()->user()), 403);
    }

    private function audit(Konsultasi $konsultasi, string $aksi, ?array $payload = null): void
    {
        KonsultasiAuditLog::create([
            'konsultasi_id' => $konsultasi->id,
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'payload' => $payload,
        ]);
    }

    private function ensureConsultationAccess(): void
    {
        abort_unless(auth()->check() && (auth()->user()->isDokter() || auth()->user()->isAdmin()), 403);
    }

    private function notifyUser(?User $recipient, Konsultasi $konsultasi, string $eventType): void
    {
        if (!$recipient || (int) $recipient->id === (int) auth()->id()) {
            return;
        }

        $recipient->notify(new KonsultasiActivityNotification(
            $konsultasi,
            auth()->user(),
            $eventType
        ));
    }

    private function markRelatedNotificationsAsRead(Konsultasi $konsultasi, User $user): void
    {
        $related = $user->unreadNotifications()
            ->where('type', KonsultasiActivityNotification::class)
            ->get()
            ->filter(function ($notification) use ($konsultasi) {
                return (int) ($notification->data['konsultasi_id'] ?? 0) === (int) $konsultasi->id;
            });

        if ($related->isNotEmpty()) {
            $related->markAsRead();
        }
    }

    private function consentOptions(): array
    {
        return [
            Konsultasi::CONSENT_MENUNGGU => 'Menunggu',
            Konsultasi::CONSENT_DIBERIKAN => 'Diberikan',
            Konsultasi::CONSENT_DITOLAK => 'Ditolak',
        ];
    }

    private function consentMethods(): array
    {
        return [
            'lisan' => 'Lisan',
            'tertulis' => 'Tertulis',
            'digital' => 'Digital',
        ];
    }
}
