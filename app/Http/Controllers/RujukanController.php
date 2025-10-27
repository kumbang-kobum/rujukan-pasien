<?php

namespace App\Http\Controllers;

use App\Models\Rujukan;
use App\Models\Kunjungan;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Notifications\RujukanMasukNotification;

class RujukanController extends Controller
{
    public function index(Request $request)
    {
        $q = Rujukan::query()->with(['kunjungan.pasien','rsAsal','rsTujuan','dokterTujuan','penerima']);

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
        if (!empty($rsTujuan)) {
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
            'kunjungan_id'          => ['required','exists:kunjungan,id'],
            // kirimkan dari form sbg hidden agar transparan
            'rumah_sakit_asal_id'   => ['required','in:'.$rsAsalId],
            'rumah_sakit_tujuan_id' => ['required','exists:rumah_sakit,id','different:rumah_sakit_asal_id'],
            'dokter_tujuan_id'      => [
                'required',
                Rule::exists('users','id')->where(function ($q) use ($request) {
                    $q->where('role','dokter')
                      ->where('rumah_sakit_id', $request->rumah_sakit_tujuan_id);
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

        $dokter = User::find($request->dokter_tujuan_id);
        $tujuan = RumahSakit::find($request->rumah_sakit_tujuan_id);

        // jika mau khususkan hanya untuk RSUD Sungai Dareh, buka IF berikut:
        if ($dokter && filter_var($dokter->email, FILTER_VALIDATE_EMAIL)) {
            // contoh pembatasan opsional:
            // if (str($tujuan->nama)->lower()->contains('rsud sungai dareh')) {
                $dokter->notify(new RujukanMasukNotification($rujukan, auth()->user()));
            // }
        }

        return redirect()->route('rujukan.index')->with('success','Rujukan berhasil ditambahkan.');
    }

    public function edit(Rujukan $rujukan)
    {
        $kunjungan   = Kunjungan::with('pasien')->orderByDesc('tanggal_kunjungan')->get();
        $rsAsalId    = (int) $rujukan->rumah_sakit_asal_id;

        $rumahSakitTujuan = RumahSakit::where('id', '!=', $rsAsalId)->orderBy('nama')->get();

        // dokter untuk RS tujuan yang sudah tersimpan
        $dokter = User::where('role','dokter')
            ->where('rumah_sakit_id', $rujukan->rumah_sakit_tujuan_id)
            ->orderBy('name')->get();

        return view('rujukan.edit', compact('rujukan','kunjungan','rumahSakitTujuan','dokter','rsAsalId'));
    }

    public function update(Request $request, Rujukan $rujukan)
    {
        $rsAsalId = (int) $rujukan->rumah_sakit_asal_id; // RS asal tidak boleh diubah sembarang

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

        // ambil nilai lama
        $oldDokterId = $rujukan->dokter_tujuan_id;
        $oldRsTujuan = $rujukan->rumah_sakit_tujuan_id;

        // ... validasi & $rujukan->update([...]) seperti sekarang ...

        // jika tujuan/dokter berubah, kirim notifikasi lagi
        if ($oldDokterId != $rujukan->dokter_tujuan_id || $oldRsTujuan != $rujukan->rumah_sakit_tujuan_id) {
            $dokterBaru = User::find($rujukan->dokter_tujuan_id);
            if ($dokterBaru && filter_var($dokterBaru->email, FILTER_VALIDATE_EMAIL)) {
                $dokterBaru->notify(new RujukanMasukNotification($rujukan, auth()->user()));
            }
        }

        return redirect()->route('rujukan.index')->with('success','Rujukan berhasil diperbarui.');
    }

    public function destroy(Rujukan $rujukan)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
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
        return view('rujukan.show', compact('rujukan'));
    }
}
