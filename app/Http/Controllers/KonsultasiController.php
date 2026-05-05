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
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class KonsultasiController extends Controller
{
    private function ensureClinicalDoctor(): void
    {
        abort_unless(auth()->user()?->isDokter() || auth()->user()?->isAdmin(), 403);
    }

    private function assertViewable(Konsultasi $konsultasi): void
    {
        $user = auth()->user();

        abort_unless($konsultasi->isVisibleTo($user), 403);
    }

    private function assertSourceAccess(Konsultasi $konsultasi): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return;
        }

        abort_unless(
            $user->isDokter() &&
            (
                (int) $user->id === (int) $konsultasi->dokter_pengirim_id ||
                (int) $user->rumah_sakit_id === (int) $konsultasi->rumah_sakit_asal_id
            ),
            403
        );
    }

    private function assertTargetDoctorAccess(Konsultasi $konsultasi): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return;
        }

        abort_unless(
            $user->isDokter() && (int) $user->id === (int) $konsultasi->dokter_tujuan_id,
            403
        );
    }

    private function assertParticipant(Konsultasi $konsultasi): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return;
        }

        abort_unless(
            $user->isDokter() &&
            in_array((int) $user->id, [(int) $konsultasi->dokter_pengirim_id, (int) $konsultasi->dokter_tujuan_id], true),
            403
        );
    }

    private function generateNoKonsultasi(): string
    {
        $prefix = 'KON-' . now()->format('Ymd');
        $last = Konsultasi::where('no_konsultasi', 'like', $prefix . '-%')
            ->orderByDesc('no_konsultasi')
            ->value('no_konsultasi');

        $nextNumber = 1;
        if ($last) {
            $parts = explode('-', $last);
            $nextNumber = ((int) end($parts)) + 1;
        }

        return $prefix . '-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    private function consentReady(array $data): bool
    {
        return !empty($data['consent_confirmed'])
            && !empty($data['consent_granted_by_name'])
            && !empty($data['consent_granted_by_role'])
            && !empty($data['consent_method'])
            && !empty($data['consent_granted_at']);
    }

    private function audit(Konsultasi $konsultasi, string $eventType, string $deskripsi, array $payload = []): void
    {
        KonsultasiAuditLog::create([
            'konsultasi_id' => $konsultasi->id,
            'actor_user_id' => auth()->id(),
            'event_type' => $eventType,
            'deskripsi' => $deskripsi,
            'payload' => $payload ?: null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    private function notifyUsers(array $recipientIds, Konsultasi $konsultasi, string $title, string $message, string $category): void
    {
        if (!Schema::hasTable('notifications')) {
            return;
        }

        $recipientIds = collect($recipientIds)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->reject(fn ($id) => $id === (int) auth()->id())
            ->unique()
            ->values();

        if ($recipientIds->isEmpty()) {
            return;
        }

        $recipients = User::whereIn('id', $recipientIds)->get();

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::send(
            $recipients,
            new KonsultasiActivityNotification($konsultasi, $title, $message, $category, auth()->user())
        );
    }

    private function markRelatedNotificationsAsRead(Konsultasi $konsultasi): void
    {
        if (!Schema::hasTable('notifications') || !auth()->check()) {
            return;
        }

        $notifications = auth()->user()
            ->unreadNotifications
            ->filter(fn ($notification) => (int) ($notification->data['konsultasi_id'] ?? 0) === (int) $konsultasi->id);

        if ($notifications->isNotEmpty()) {
            $notifications->markAsRead();
        }
    }

    private function markConsultationAsRead(Konsultasi $konsultasi): void
    {
        $user = auth()->user();

        if (
            !$user ||
            !$user->isDokter() ||
            (int) $user->id !== (int) $konsultasi->dokter_tujuan_id ||
            $konsultasi->status !== Konsultasi::STATUS_SUBMITTED
        ) {
            return;
        }

        $konsultasi->update([
            'status' => Konsultasi::STATUS_READ,
        ]);

        $this->audit($konsultasi, 'read', 'Konsultasi telah dibaca dokter tujuan.');
    }

    private function payloadForSave(Request $request, Kunjungan $kunjungan, RumahSakit $rsTujuan, User $dokterTujuan, bool $submitted): array
    {
        $consentReady = $this->consentReady($request->all());

        $status = Konsultasi::STATUS_DRAFT;
        $consentStatus = 'belum_diminta';
        $submittedAt = null;

        if ($submitted && $consentReady) {
            $status = Konsultasi::STATUS_SUBMITTED;
            $consentStatus = 'disetujui';
            $submittedAt = now();
        } elseif ($submitted) {
            $status = Konsultasi::STATUS_AWAITING_CONSENT;
            $consentStatus = 'menunggu';
        } elseif ($consentReady) {
            $consentStatus = 'disetujui';
        }

        return [
            'kunjungan_id' => $kunjungan->id,
            'pasien_id' => $kunjungan->pasien_id,
            'rumah_sakit_asal_id' => (int) auth()->user()->rumah_sakit_id,
            'rumah_sakit_tujuan_id' => $rsTujuan->id,
            'dokter_pengirim_id' => auth()->id(),
            'dokter_tujuan_id' => $dokterTujuan->id,
            'patient_ihs_number' => $kunjungan->pasien?->patient_ihs_number,
            'organization_ihs_asal' => auth()->user()->rumahSakit?->organization_ihs_number,
            'organization_ihs_tujuan' => $rsTujuan->organization_ihs_number,
            'practitioner_ihs_pengirim' => auth()->user()->practitioner_ihs_number,
            'practitioner_ihs_tujuan' => $dokterTujuan->practitioner_ihs_number,
            'practitioner_role_pengirim' => auth()->user()->satusehat_practitioner_role_id,
            'practitioner_role_tujuan' => $dokterTujuan->satusehat_practitioner_role_id,
            'encounter_satusehat_id' => $kunjungan->satusehat_encounter_id,
            'judul' => $request->judul,
            'urgensi' => $request->urgensi,
            'alasan_konsultasi' => $request->alasan_konsultasi,
            'pertanyaan_klinis' => $request->pertanyaan_klinis,
            'ringkasan_klinis' => $request->ringkasan_klinis,
            'diagnosis_kerja' => $request->diagnosis_kerja,
            'hasil_penunjang' => $request->hasil_penunjang,
            'terapi_berjalan' => $request->terapi_berjalan,
            'consent_status' => $consentStatus,
            'consent_granted_by_name' => $request->consent_granted_by_name,
            'consent_granted_by_role' => $request->consent_granted_by_role,
            'consent_method' => $request->consent_method,
            'consent_granted_at' => $request->consent_granted_at,
            'consent_expires_at' => $request->consent_expires_at,
            'consent_notes' => $request->consent_notes,
            'status' => $status,
            'submitted_at' => $submittedAt,
        ];
    }

    public function index(Request $request)
    {
        $this->ensureClinicalDoctor();

        $user = auth()->user();

        $q = Konsultasi::query()
            ->visibleTo($user)
            ->with([
                'kunjungan.pasien',
                'rsAsal',
                'rsTujuan',
                'dokterPengirim',
                'dokterTujuan',
                'rujukan',
            ])
            ->withCount([
                'pesan as unread_messages_count' => fn ($query) => $query
                    ->whereNull('dibaca_at')
                    ->where('pengirim_id', '!=', $user->id),
            ]);

        if ($keyword = trim((string) $request->input('keyword', ''))) {
            $q->where(function ($w) use ($keyword) {
                $w->where('no_konsultasi', 'like', "%{$keyword}%")
                    ->orWhere('judul', 'like', "%{$keyword}%")
                    ->orWhere('alasan_konsultasi', 'like', "%{$keyword}%")
                    ->orWhere('pertanyaan_klinis', 'like', "%{$keyword}%")
                    ->orWhereHas('kunjungan', function ($wk) use ($keyword) {
                        $wk->where('no_rawat', 'like', "%{$keyword}%")
                            ->orWhereHas('pasien', function ($wp) use ($keyword) {
                                $wp->where('no_rkm_medis', 'like', "%{$keyword}%")
                                    ->orWhere('nama', 'like', "%{$keyword}%");
                            });
                    });
            });
        }

        if ($status = $request->input('status')) {
            $q->where('status', $status);
        }

        if ($urgensi = $request->input('urgensi')) {
            $q->where('urgensi', $urgensi);
        }

        if ($request->boolean('tujuan_saya')) {
            $q->where('dokter_tujuan_id', auth()->id());
        }

        $konsultasi = $q->latest()->paginate(10)->withQueryString();

        return view('konsultasi.index', compact('konsultasi'));
    }

    public function create(Request $request)
    {
        $this->ensureClinicalDoctor();

        $user = auth()->user();
        $rsAsalId = (int) $user->rumah_sakit_id;

        $kunjungan = Kunjungan::with(['pasien', 'dokter'])
            ->when(!$user->isAdmin(), fn ($query) => $query->where('rumah_sakit_id', $rsAsalId))
            ->orderByDesc('tanggal_kunjungan')
            ->get();

        $rumahSakitTujuan = RumahSakit::where('id', '!=', $rsAsalId)->orderBy('nama')->get();
        $dokterTujuan = collect();
        $selectedKunjunganId = $request->integer('kunjungan_id') ?: null;

        return view('konsultasi.create', compact('kunjungan', 'rumahSakitTujuan', 'dokterTujuan', 'selectedKunjunganId'));
    }

    public function store(Request $request)
    {
        $this->ensureClinicalDoctor();

        $request->validate([
            'kunjungan_id' => ['required', Rule::exists(Kunjungan::class, 'id')],
            'rumah_sakit_tujuan_id' => ['required', Rule::exists(RumahSakit::class, 'id')],
            'dokter_tujuan_id' => [
                'required',
                Rule::exists(User::class, 'id')->where(function ($query) use ($request) {
                    $query->where('role', 'dokter')
                        ->where('rumah_sakit_id', $request->rumah_sakit_tujuan_id);
                }),
            ],
            'judul' => ['required', 'string', 'max:255'],
            'urgensi' => ['required', 'in:rutin,segera,gawat'],
            'alasan_konsultasi' => ['required', 'string'],
            'pertanyaan_klinis' => ['required', 'string'],
            'ringkasan_klinis' => ['nullable', 'string'],
            'diagnosis_kerja' => ['nullable', 'string'],
            'hasil_penunjang' => ['nullable', 'string'],
            'terapi_berjalan' => ['nullable', 'string'],
            'consent_granted_by_name' => ['nullable', 'string', 'max:255'],
            'consent_granted_by_role' => ['nullable', 'string', 'max:100'],
            'consent_method' => ['nullable', 'string', 'max:100'],
            'consent_granted_at' => ['nullable', 'date'],
            'consent_expires_at' => ['nullable', 'date', 'after_or_equal:consent_granted_at'],
            'consent_notes' => ['nullable', 'string'],
            'action' => ['required', 'in:draft,submit'],
        ]);

        $kunjungan = Kunjungan::with(['pasien', 'dokter'])->findOrFail($request->kunjungan_id);
        $rsTujuan = RumahSakit::findOrFail($request->rumah_sakit_tujuan_id);
        $dokterTujuan = User::findOrFail($request->dokter_tujuan_id);

        abort_unless((int) $rsTujuan->id !== (int) auth()->user()->rumah_sakit_id, 422);

        if (!auth()->user()->isAdmin()) {
            abort_unless((int) $kunjungan->rumah_sakit_id === (int) auth()->user()->rumah_sakit_id, 403);
        }

        $konsultasi = Konsultasi::create(array_merge(
            ['no_konsultasi' => $this->generateNoKonsultasi()],
            $this->payloadForSave($request, $kunjungan, $rsTujuan, $dokterTujuan, $request->action === 'submit')
        ));

        KonsultasiPesan::create([
            'konsultasi_id' => $konsultasi->id,
            'pengirim_id' => auth()->id(),
            'jenis_pesan' => 'question',
            'isi_pesan' => trim(implode("\n\n", array_filter([
                'Pertanyaan klinis: ' . $request->pertanyaan_klinis,
                $request->ringkasan_klinis ? 'Ringkasan klinis: ' . $request->ringkasan_klinis : null,
                $request->diagnosis_kerja ? 'Diagnosis kerja: ' . $request->diagnosis_kerja : null,
            ]))),
            'status' => 'sent',
        ]);

        $konsultasi->update(['last_message_at' => now()]);

        $this->audit($konsultasi, 'created', 'Konsultasi dibuat.', [
            'status' => $konsultasi->status,
            'target_doctor_id' => $dokterTujuan->id,
            'target_rs_id' => $rsTujuan->id,
        ]);

        if ($konsultasi->status === Konsultasi::STATUS_SUBMITTED) {
            $this->audit($konsultasi, 'submitted', 'Konsultasi dikirim ke dokter tujuan.');
            $this->notifyUsers(
                [$dokterTujuan->id],
                $konsultasi,
                'Konsultasi baru masuk',
                'Konsultasi ' . $konsultasi->no_konsultasi . ' dari dr. ' . auth()->user()->name . ' menunggu tindak lanjut Anda.',
                'new_consultation'
            );
        }

        return redirect()->route('konsultasi.show', $konsultasi)
            ->with('success', $konsultasi->status === Konsultasi::STATUS_SUBMITTED
                ? 'Konsultasi berhasil dibuat dan dikirim.'
                : 'Konsultasi berhasil disimpan.');
    }

    public function show(Konsultasi $konsultasi)
    {
        $this->assertViewable($konsultasi);

        $this->markConsultationAsRead($konsultasi);
        $konsultasi->refresh();

        $konsultasi->load([
            'kunjungan.pasien',
            'rsAsal',
            'rsTujuan',
            'dokterPengirim',
            'dokterTujuan',
            'rujukan',
            'pesan.pengirim',
            'auditLogs.actor',
        ]);

        KonsultasiPesan::where('konsultasi_id', $konsultasi->id)
            ->where('pengirim_id', '!=', auth()->id())
            ->whereNull('dibaca_at')
            ->update([
                'dibaca_at' => now(),
                'status' => 'read',
                'updated_at' => now(),
            ]);

        $this->markRelatedNotificationsAsRead($konsultasi);
        $this->audit($konsultasi, 'viewed', 'Detail konsultasi dibuka.');

        return view('konsultasi.show', compact('konsultasi'));
    }

    public function edit(Konsultasi $konsultasi)
    {
        $this->assertSourceAccess($konsultasi);

        abort_unless(in_array($konsultasi->status, Konsultasi::sourceEditableStatuses(), true), 403);

        $user = auth()->user();
        $rsAsalId = (int) $user->rumah_sakit_id;

        $kunjungan = Kunjungan::with(['pasien', 'dokter'])
            ->when(!$user->isAdmin(), fn ($query) => $query->where('rumah_sakit_id', $rsAsalId))
            ->orderByDesc('tanggal_kunjungan')
            ->get();

        $rumahSakitTujuan = RumahSakit::where('id', '!=', $rsAsalId)->orderBy('nama')->get();
        $dokterTujuan = User::where('role', 'dokter')
            ->where('rumah_sakit_id', $konsultasi->rumah_sakit_tujuan_id)
            ->orderBy('name')
            ->get();

        return view('konsultasi.edit', compact('konsultasi', 'kunjungan', 'rumahSakitTujuan', 'dokterTujuan'));
    }

    public function update(Request $request, Konsultasi $konsultasi)
    {
        $this->assertSourceAccess($konsultasi);

        abort_unless(in_array($konsultasi->status, Konsultasi::sourceEditableStatuses(), true), 403);

        $request->validate([
            'kunjungan_id' => ['required', Rule::exists(Kunjungan::class, 'id')],
            'rumah_sakit_tujuan_id' => ['required', Rule::exists(RumahSakit::class, 'id')],
            'dokter_tujuan_id' => [
                'required',
                Rule::exists(User::class, 'id')->where(function ($query) use ($request) {
                    $query->where('role', 'dokter')
                        ->where('rumah_sakit_id', $request->rumah_sakit_tujuan_id);
                }),
            ],
            'judul' => ['required', 'string', 'max:255'],
            'urgensi' => ['required', 'in:rutin,segera,gawat'],
            'alasan_konsultasi' => ['required', 'string'],
            'pertanyaan_klinis' => ['required', 'string'],
            'ringkasan_klinis' => ['nullable', 'string'],
            'diagnosis_kerja' => ['nullable', 'string'],
            'hasil_penunjang' => ['nullable', 'string'],
            'terapi_berjalan' => ['nullable', 'string'],
            'consent_granted_by_name' => ['nullable', 'string', 'max:255'],
            'consent_granted_by_role' => ['nullable', 'string', 'max:100'],
            'consent_method' => ['nullable', 'string', 'max:100'],
            'consent_granted_at' => ['nullable', 'date'],
            'consent_expires_at' => ['nullable', 'date', 'after_or_equal:consent_granted_at'],
            'consent_notes' => ['nullable', 'string'],
            'action' => ['required', 'in:draft,submit'],
        ]);

        $kunjungan = Kunjungan::with(['pasien', 'dokter'])->findOrFail($request->kunjungan_id);
        $rsTujuan = RumahSakit::findOrFail($request->rumah_sakit_tujuan_id);
        $dokterTujuan = User::findOrFail($request->dokter_tujuan_id);

        abort_unless((int) $rsTujuan->id !== (int) auth()->user()->rumah_sakit_id, 422);

        if (!auth()->user()->isAdmin()) {
            abort_unless((int) $kunjungan->rumah_sakit_id === (int) auth()->user()->rumah_sakit_id, 403);
        }

        $oldStatus = $konsultasi->status;
        $oldTargetDoctorId = (int) $konsultasi->dokter_tujuan_id;

        $payload = $this->payloadForSave($request, $kunjungan, $rsTujuan, $dokterTujuan, $request->action === 'submit');
        if ($konsultasi->submitted_at && $payload['status'] === Konsultasi::STATUS_SUBMITTED) {
            $payload['submitted_at'] = $konsultasi->submitted_at;
        }

        $konsultasi->update($payload);

        $this->audit($konsultasi, 'updated', 'Konsultasi diperbarui.', [
            'status' => $konsultasi->status,
        ]);

        if (
            $konsultasi->status === Konsultasi::STATUS_SUBMITTED &&
            ($oldStatus !== Konsultasi::STATUS_SUBMITTED || $oldTargetDoctorId !== (int) $dokterTujuan->id)
        ) {
            $this->notifyUsers(
                [$dokterTujuan->id],
                $konsultasi,
                'Konsultasi perlu ditinjau',
                'Konsultasi ' . $konsultasi->no_konsultasi . ' telah dikirim atau dialihkan kepada Anda.',
                'consultation_submitted'
            );
        }

        return redirect()->route('konsultasi.show', $konsultasi)->with('success', 'Konsultasi berhasil diperbarui.');
    }

    public function submit(Konsultasi $konsultasi)
    {
        $this->assertSourceAccess($konsultasi);

        abort_unless(in_array($konsultasi->status, [
            Konsultasi::STATUS_DRAFT,
            Konsultasi::STATUS_AWAITING_CONSENT,
        ], true), 403);

        abort_unless(
            $konsultasi->consent_status === 'disetujui' &&
            filled($konsultasi->consent_granted_by_name) &&
            filled($konsultasi->consent_granted_by_role) &&
            filled($konsultasi->consent_method) &&
            filled($konsultasi->consent_granted_at),
            422
        );

        $konsultasi->update([
            'status' => Konsultasi::STATUS_SUBMITTED,
            'submitted_at' => $konsultasi->submitted_at ?: now(),
        ]);

        $this->audit($konsultasi, 'submitted', 'Konsultasi dikirim dari draft/menunggu consent.');
        $this->notifyUsers(
            [$konsultasi->dokter_tujuan_id],
            $konsultasi,
            'Konsultasi baru masuk',
            'Konsultasi ' . $konsultasi->no_konsultasi . ' dari dr. ' . auth()->user()->name . ' menunggu tindak lanjut Anda.',
            'new_consultation'
        );

        return back()->with('success', 'Konsultasi berhasil dikirim.');
    }

    public function ubahStatus(Konsultasi $konsultasi, string $status)
    {
        $this->assertTargetDoctorAccess($konsultasi);

        abort_unless(in_array($status, [
            Konsultasi::STATUS_ACCEPTED,
            Konsultasi::STATUS_REJECTED,
        ], true), 404);
        abort_unless(in_array($konsultasi->status, [
            Konsultasi::STATUS_SUBMITTED,
            Konsultasi::STATUS_READ,
        ], true), 403);

        if ($status === Konsultasi::STATUS_ACCEPTED) {
            $konsultasi->update([
                'status' => Konsultasi::STATUS_ACCEPTED,
                'accepted_at' => $konsultasi->accepted_at ?: now(),
            ]);
            $this->audit($konsultasi, 'accepted', 'Konsultasi diterima dokter tujuan.');
            $this->notifyUsers(
                [$konsultasi->dokter_pengirim_id],
                $konsultasi,
                'Konsultasi diterima',
                'Konsultasi ' . $konsultasi->no_konsultasi . ' telah diterima oleh dr. ' . auth()->user()->name . '.',
                'consultation_accepted'
            );

            return back()->with('success', 'Konsultasi diterima.');
        }

        $konsultasi->update(['status' => Konsultasi::STATUS_REJECTED]);
        $this->audit($konsultasi, 'rejected', 'Konsultasi ditolak dokter tujuan.');
        $this->notifyUsers(
            [$konsultasi->dokter_pengirim_id],
            $konsultasi,
            'Konsultasi ditolak',
            'Konsultasi ' . $konsultasi->no_konsultasi . ' ditolak oleh dr. ' . auth()->user()->name . '.',
            'consultation_rejected'
        );

        return back()->with('success', 'Konsultasi ditolak.');
    }

    public function balas(Request $request, Konsultasi $konsultasi)
    {
        $this->assertParticipant($konsultasi);

        abort_unless(!$konsultasi->isTerminal(), 403);
        abort_unless($konsultasi->isReplyable(), 403);

        $isTargetDoctor = (int) auth()->id() === (int) $konsultasi->dokter_tujuan_id;
        $allowedTypes = $isTargetDoctor
            ? ['message', 'answer', 'request_more_info']
            : ['message'];

        $request->validate([
            'jenis_pesan' => ['required', Rule::in($allowedTypes)],
            'isi_pesan' => ['required', 'string'],
        ]);

        $jenis = $request->jenis_pesan;

        KonsultasiPesan::create([
            'konsultasi_id' => $konsultasi->id,
            'pengirim_id' => auth()->id(),
            'jenis_pesan' => $jenis,
            'isi_pesan' => $request->isi_pesan,
            'status' => 'sent',
        ]);

        $newStatus = $konsultasi->status;
        if ($isTargetDoctor && $jenis === 'request_more_info') {
            $newStatus = Konsultasi::STATUS_AWAITING_MORE_INFO;
        } elseif ($isTargetDoctor && $jenis === 'answer') {
            $newStatus = Konsultasi::STATUS_ANSWERED;
        } elseif ($isTargetDoctor) {
            $newStatus = Konsultasi::STATUS_IN_DISCUSSION;
        } elseif ($jenis === 'message') {
            $newStatus = Konsultasi::STATUS_IN_DISCUSSION;
        }

        $updates = [
            'status' => $newStatus,
            'last_message_at' => now(),
        ];

        if ($newStatus === Konsultasi::STATUS_ANSWERED) {
            $updates['answered_at'] = now();
        }

        $konsultasi->update($updates);

        $recipientId = $isTargetDoctor ? $konsultasi->dokter_pengirim_id : $konsultasi->dokter_tujuan_id;
        $notificationTitle = 'Pesan baru pada konsultasi';
        $notificationMessage = 'dr. ' . auth()->user()->name . ' mengirim pesan baru pada konsultasi ' . $konsultasi->no_konsultasi . '.';

        if ($jenis === 'answer') {
            $notificationTitle = 'Jawaban konsultasi masuk';
            $notificationMessage = 'dr. ' . auth()->user()->name . ' mengirim jawaban klinis untuk konsultasi ' . $konsultasi->no_konsultasi . '.';
        } elseif ($jenis === 'request_more_info') {
            $notificationTitle = 'Konsultasi butuh info tambahan';
            $notificationMessage = 'dr. ' . auth()->user()->name . ' meminta info tambahan untuk konsultasi ' . $konsultasi->no_konsultasi . '.';
        }

        $this->notifyUsers(
            [$recipientId],
            $konsultasi,
            $notificationTitle,
            $notificationMessage,
            'consultation_reply'
        );

        $this->audit($konsultasi, 'message_sent', 'Pesan konsultasi ditambahkan.', [
            'jenis_pesan' => $jenis,
            'status_baru' => $newStatus,
        ]);

        return back()->with('success', 'Pesan konsultasi berhasil dikirim.');
    }

    public function tutup(Konsultasi $konsultasi)
    {
        $this->assertParticipant($konsultasi);

        abort_unless(!$konsultasi->isTerminal(), 403);
        abort_unless(!in_array($konsultasi->status, [
            Konsultasi::STATUS_DRAFT,
            Konsultasi::STATUS_AWAITING_CONSENT,
        ], true), 403);

        $konsultasi->update([
            'status' => Konsultasi::STATUS_CLOSED,
            'closed_at' => now(),
        ]);

        $this->audit($konsultasi, 'closed', 'Konsultasi ditutup.');

        return back()->with('success', 'Konsultasi ditutup.');
    }

    public function eskalasi(Konsultasi $konsultasi)
    {
        $this->assertSourceAccess($konsultasi);

        abort_unless(!$konsultasi->isTerminal(), 403);
        abort_unless(!$konsultasi->escalated_to_rujukan_id, 422);
        abort_unless(!in_array($konsultasi->status, [
            Konsultasi::STATUS_DRAFT,
            Konsultasi::STATUS_AWAITING_CONSENT,
        ], true), 403);

        $alasanRujukan = trim(implode("\n\n", array_filter([
            'Asal konsultasi: ' . $konsultasi->no_konsultasi,
            'Judul: ' . $konsultasi->judul,
            'Alasan konsultasi: ' . $konsultasi->alasan_konsultasi,
            'Pertanyaan klinis: ' . $konsultasi->pertanyaan_klinis,
            $konsultasi->diagnosis_kerja ? 'Diagnosis kerja: ' . $konsultasi->diagnosis_kerja : null,
            $konsultasi->ringkasan_klinis ? 'Ringkasan klinis: ' . $konsultasi->ringkasan_klinis : null,
            $konsultasi->terapi_berjalan ? 'Terapi berjalan: ' . $konsultasi->terapi_berjalan : null,
        ])));

        $rujukan = Rujukan::create([
            'kunjungan_id' => $konsultasi->kunjungan_id,
            'origin_konsultasi_id' => $konsultasi->id,
            'rumah_sakit_asal_id' => $konsultasi->rumah_sakit_asal_id,
            'rumah_sakit_tujuan_id' => $konsultasi->rumah_sakit_tujuan_id,
            'dokter_tujuan_id' => $konsultasi->dokter_tujuan_id,
            'alasan' => $konsultasi->judul,
            'alasan_rujukan' => $alasanRujukan,
            'catatan' => 'Rujukan resmi hasil eskalasi dari konsultasi klinis lintas rumah sakit.',
            'status' => 'menunggu',
        ]);

        $konsultasi->update([
            'status' => Konsultasi::STATUS_ESCALATED,
            'escalated_to_rujukan_id' => $rujukan->id,
            'closed_at' => now(),
        ]);

        $this->audit($konsultasi, 'escalated', 'Konsultasi dieskalasi menjadi rujukan resmi.', [
            'rujukan_id' => $rujukan->id,
        ]);

        return redirect()->route('rujukan.show', $rujukan)
            ->with('success', 'Konsultasi berhasil dilanjutkan menjadi rujukan resmi.');
    }
}
