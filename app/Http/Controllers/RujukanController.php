<?php

namespace App\Http\Controllers;

use App\Models\Rujukan;
use App\Models\Kunjungan;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RujukanMasukNotification;

class RujukanController extends Controller
{
    
    // di dalam class RujukanController
    private function assertViewable(Rujukan $rujukan): void
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) return;
    
        $rsId = (int) $user->rumah_sakit_id;
        abort_unless(
            in_array($rsId, [(int)$rujukan->rumah_sakit_asal_id, (int)$rujukan->rumah_sakit_tujuan_id], true),
            403
        );
    }
    
    /** hanya RS asal (atau admin) yang boleh mengubah isi rujukan */
    private function assertManage(Rujukan $rujukan): void
    {
        $user = auth()->user();
        abort_unless(
            $user->isSuperAdmin()
            || (int)$user->rumah_sakit_id === (int)$rujukan->rumah_sakit_asal_id
            || (int)$user->rumah_sakit_id === (int)$rujukan->rumah_sakit_tujuan_id,
            403
        );
    }

    public function index(Request $request)
    {
        $user = auth()->user();
    
        $q = Rujukan::query()
            ->visibleTo($user) // ⬅️ kunci utama pembatasan list
            ->with(['kunjungan.pasien','rsAsal','rsTujuan','dokterTujuan','penerima']);

        // Keyword: no_rawat / no_rkm_medis / nama pasien / alasan / catatan
        if ($kw = trim($request->input('keyword', ''))) {
            $q->where(function($w) use ($kw) {
                $w->where('alasan','like',"%{$kw}%")
                ->orWhere('alasan_rujukan','like',"%{$kw}%")
                ->orWhere('catatan','like',"%{$kw}%")
                ->orWhereHas('kunjungan', function($wk) use ($kw){
                    $wk->where('no_rawat','like',"%{$kw}%")
                        ->orWhereHas('pasien', function($wp) use ($kw){
                            $wp->where('no_rkm_medis','like',"%{$kw}%")
                                ->orWhere('nama','like',"%{$kw}%");
                        });
                });
            });
        }

        // Status
        if ($status = $request->input('status')) {
            if (in_array($status, ['menunggu','diterima','ditolak'], true)) {
                $q->where('status', $status);
            }
        }

        // RS Asal / RS Tujuan
        if ($rsAsal = $request->input('rs_asal_id')) {
            $q->where('rumah_sakit_asal_id', $rsAsal);
        }
        if ($rsTujuan = $request->input('rs_tujuan_id')) {
            $q->where('rumah_sakit_tujuan_id', $rsTujuan);
        }

        // Dokter tujuan
        if ($dokterId = $request->input('dokter_tujuan_id')) {
            $q->where('dokter_tujuan_id', $dokterId);
        }

        // Hanya rujukan ke RS saya
        if ($request->boolean('tujuan_saya') && auth()->check()) {
            $q->where('rumah_sakit_tujuan_id', auth()->user()->rumah_sakit_id);
        }

        // Rentang tanggal dibuat
        if ($from = $request->input('created_from')) {
            $q->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('created_to')) {
            $q->whereDate('created_at', '<=', $to);
        }

        // Sorting
        $allowedSort = ['created_at','status','id'];
        $sortBy  = in_array($request->input('sort_by'), $allowedSort) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $q->orderBy($sortBy, $sortDir);

        // Per halaman
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10,25,50,100])) $perPage = 10;

        $rujukan = $q->paginate($perPage)->withQueryString();

        // Dropdown sumber data
        $rsList = RumahSakit::orderBy('nama')->get(['id','nama']);
        $dokterList = collect();
        if ($rsTujuan = $request->input('rs_tujuan_id')) {
            $dokterList = User::where('role','dokter')
                ->where('rumah_sakit_id', $rsTujuan)
                ->orderBy('name')->get(['id','name']);
        }

        return view('rujukan.index', compact('rujukan','rsList','dokterList'));
    }

    public function create()
    {
        // daftar kunjungan (silakan sesuaikan filter)
        $kunjungan   = Kunjungan::with('pasien')->orderByDesc('tanggal_kunjungan')->get();
        $rsAsalId    = auth()->user()->rumah_sakit_id;

        // RS tujuan: exclude RS asal
        $rumahSakitTujuan = RumahSakit::where('id', '!=', $rsAsalId)
            ->orderBy('nama')->get();

        // dokter diisi di front-end via AJAX (awal kosong)
        $dokterTujuan = collect();

        return view('rujukan.create', [
            'kunjungan'        => $kunjungan,
            'rumahSakit'       => $rumahSakitTujuan,
            'dokter'           => $dokterTujuan,
            'rsAsalId'         => $rsAsalId,
        ]);
    }

    public function store(Request $request)
    {
        $rsAsalId = (int) auth()->user()->rumah_sakit_id;
    
        $request->validate([
            'kunjungan_id'          => ['required', Rule::exists(Kunjungan::class, 'id')],
            'rumah_sakit_asal_id'   => ['required', Rule::in([$rsAsalId])],
            'rumah_sakit_tujuan_id' => ['required', Rule::exists(RumahSakit::class, 'id'), 'different:rumah_sakit_asal_id'],
            'dokter_tujuan_id'      => [
                'required',
                Rule::exists(User::class,'id')->where(function ($q) use ($request) {
                    $q->where('role','dokter')->where('rumah_sakit_id', $request->rumah_sakit_tujuan_id);
                }),
            ],
            // ⬇️ multi-pilih tembusan (opsional)
            'dokter_cc_ids'         => ['nullable','array'],
            'dokter_cc_ids.*'       => [
                'integer','different:dokter_tujuan_id',
                Rule::exists(User::class,'id')->where(function ($q) use ($request) {
                    $q->where('role','dokter')->where('rumah_sakit_id', $request->rumah_sakit_tujuan_id);
                }),
            ],
            'alasan'                => ['required','string','max:255'],
            'alasan_rujukan'        => ['nullable','string'],
            'catatan'               => ['nullable','string'],
        ]);
    
        $rujukan = Rujukan::create([
            'kunjungan_id'           => $request->kunjungan_id,
            'rumah_sakit_asal_id'    => $rsAsalId,
            'rumah_sakit_tujuan_id'  => $request->rumah_sakit_tujuan_id,
            'dokter_tujuan_id'       => $request->dokter_tujuan_id,
            'alasan'                 => $request->alasan,
            'alasan_rujukan'         => $request->alasan_rujukan ?? '-',
            'catatan'                => $request->catatan,
            'status'                 => 'menunggu',
        ]);
    
        $ccIds = collect($request->input('dokter_cc_ids', []))
            ->map(function ($v) { return (int) $v; })
            ->unique()
            ->values();
        
        $rujukan->dokterCc()->sync($ccIds);
        
        // kirim email ke dokter utama + semua CC
        $recipientIds = $ccIds->push((int)$request->dokter_tujuan_id)->unique()->values();
        $recipients   = User::whereIn('id', $recipientIds)->whereNotNull('email')->get();
        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new RujukanMasukNotification($rujukan, auth()->user()));
        }
    
        return redirect()->route('rujukan.index')
            ->with('success','Rujukan berhasil ditambahkan & email dikirim ke dokter tujuan/tembusan.');
    }

    public function edit(Rujukan $rujukan)
    {
        $this->assertViewable($rujukan);
        $this->assertManage($rujukan);
    
        $kunjungan = Kunjungan::with('pasien')->orderByDesc('tanggal_kunjungan')->get();
        $rsAsalId  = (int) $rujukan->rumah_sakit_asal_id;
    
        $rumahSakitTujuan = RumahSakit::where('id','!=',$rsAsalId)->orderBy('nama')->get();
    
        $dokter = User::where('role','dokter')
            ->where('rumah_sakit_id', $rujukan->rumah_sakit_tujuan_id)
            ->orderBy('name')->get();
    
        $ccTerpilih = $rujukan->dokterCc()->pluck('users.id')->all();
    
        return view('rujukan.edit', compact('rujukan','kunjungan','rumahSakitTujuan','dokter','rsAsalId','ccTerpilih'));
    }

    public function update(Request $request, Rujukan $rujukan)
    {
        $this->assertViewable($rujukan);
        $this->assertManage($rujukan);
    
        $rsAsalId = (int) $rujukan->rumah_sakit_asal_id;
    
        // SIMPAN NILAI LAMA SEBELUM UPDATE
        $oldDokterId = (int) $rujukan->dokter_tujuan_id;
        $oldRsTujuan = (int) $rujukan->rumah_sakit_tujuan_id;
    
        $request->validate([
            'kunjungan_id'          => ['required','exists:kunjungan,id'],
            'rumah_sakit_asal_id'   => ['required','in:'.$rsAsalId],
            'rumah_sakit_tujuan_id' => ['required','exists:rumah_sakit,id','different:rumah_sakit_asal_id'],
            'dokter_tujuan_id'      => [
                'required',
                Rule::exists('users','id')->where(function ($q) use ($request) {
                    $q->where('role','dokter')
                      ->where('rumah_sakit_id', $request->rumah_sakit_tujuan_id);
                }),
            ],
            // tambahkan validasi CC (opsional, tapi disarankan)
            'dokter_cc_ids'   => ['nullable','array'],
            'dokter_cc_ids.*' => [
                'integer','different:dokter_tujuan_id',
                Rule::exists('users','id')->where(function ($q) use ($request) {
                    $q->where('role','dokter')
                      ->where('rumah_sakit_id', $request->rumah_sakit_tujuan_id);
                }),
            ],
            'alasan'                => ['required','string','max:255'],
            'alasan_rujukan'        => ['nullable','string'],
            'catatan'               => ['nullable','string'],
            'status'                => ['required','in:menunggu,diterima,ditolak'],
        ]);
    
        $rujukan->update([
            'kunjungan_id'           => $request->kunjungan_id,
            'rumah_sakit_asal_id'    => $rsAsalId,
            'rumah_sakit_tujuan_id'  => $request->rumah_sakit_tujuan_id,
            'dokter_tujuan_id'       => $request->dokter_tujuan_id,
            'alasan'                 => $request->alasan,
            'alasan_rujukan'         => $request->alasan_rujukan ?? $rujukan->alasan_rujukan,
            'catatan'                => $request->catatan,
            'status'                 => $request->status,
        ]);
    
        // --- Kirim email bila perlu ---
        $ccIds = collect($request->input('dokter_cc_ids', []))
            ->map(function ($v) { return (int) $v; })
            ->unique()
            ->values();
        
        $rujukan->dokterCc()->sync($ccIds);
    
        $dokterBerubah = ($oldDokterId !== (int)$rujukan->dokter_tujuan_id) 
                      || ($oldRsTujuan !== (int)$rujukan->rumah_sakit_tujuan_id);
    
        // Kirim ke dokter tujuan bila berubah ATAU bila ada CC (biar si tujuan juga terima saat ada CC)
        $recipientIds = collect();
        if ($dokterBerubah || $ccIds->isNotEmpty()) {
            $recipientIds->push((int)$rujukan->dokter_tujuan_id);
        }
        $recipientIds = $recipientIds->merge($ccIds)->unique()->values();
    
        if ($recipientIds->isNotEmpty()) {
            $recipients = User::whereIn('id', $recipientIds)
                ->whereNotNull('email')
                ->get();
    
            if ($recipients->isNotEmpty()) {
                Notification::send($recipients, new RujukanMasukNotification($rujukan, auth()->user()));
            }
        }
    
        return redirect()->route('rujukan.index')->with('success','Rujukan berhasil diperbarui.');
    }

    public function destroy(Rujukan $rujukan)
    {
        $this->assertViewable($rujukan);
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        $rujukan->delete();
        
        return back()->with('success','Rujukan dihapus.');
    }

    public function ubahStatus(Request $request, Rujukan $rujukan, $status)
    {
        abort_unless(auth()->check(), 403);
        abort_unless((int) auth()->user()->rumah_sakit_id === (int) $rujukan->rumah_sakit_tujuan_id, 403);

        if (!in_array($status, ['diterima','ditolak','menunggu'], true)) {
            return back()->with('error','Status tidak valid.');
        }

        $rujukan->status = $status;
        $rujukan->penerima_id = auth()->id();
        $rujukan->save();

        return back()->with('success','Status rujukan diperbarui.');
    }

    public function show(Rujukan $rujukan)
    {
        $this->assertViewable($rujukan);
        $rujukan->load(['kunjungan.pasien', 'rsAsal', 'rsTujuan', 'dokterTujuan', 'penerima']);

        return view('rujukan.show', compact('rujukan'));
    }
}
