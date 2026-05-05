<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF; // untuk cetak PDF (setelah install barryvdh/dompdf)

class KunjunganController extends Controller
{
    /**
     * Generate no_rawat ala SIMRS: YYYY/MM/DD/00001 (urut per hari)
     */
    private function generateNoRawat(): string
    {
        $prefix = now()->format('Y/m/d'); // contoh: 2025/09/18
        $last = Kunjungan::where('no_rawat', 'like', $prefix.'/%')
            ->orderByDesc('no_rawat')
            ->value('no_rawat'); // mis: 2025/09/18/00012

        $nextNumber = 1;
        if ($last) {
            $parts = explode('/', $last);
            $seq = intval(end($parts));
            $nextNumber = $seq + 1;
        }

        return $prefix.'/'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * List kunjungan (default: hari ini) + filter
     */
    public function index(Request $request)
    {
        $query = Kunjungan::with(['pasien','dokter','user'])->latest();

        // Default tampil hari ini jika tidak ada filter tanggal
        $start = $request->start_date;
        $end   = $request->end_date;

        if ($start && $end) {
            $query->whereBetween('tanggal_kunjungan', [$start, $end]);
        } else {
            $query->whereDate('tanggal_kunjungan', now()->toDateString());
        }

        // Filter pasien (nama / no RM)
        if ($request->filled('pasien')) {
            $q = trim($request->pasien);
            $query->whereHas('pasien', function($sub) use ($q) {
                $sub->where('nama','like',"%$q%")
                    ->orWhere('no_rkm_medis','like',"%$q%");
            });
        }

        // Filter dokter
        if ($request->filled('dokter_id')) {
            $query->where('dokter_id', $request->dokter_id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_pulang', $request->status === 'pulang' ? 1 : 0);
        }

        $kunjungan = $query->paginate(10)->appends($request->all());
        $dokter = User::where('role','dokter')->orderBy('name')->get();

        return view('kunjungan.index', compact('kunjungan','dokter'));
    }

    public function create()
    {
        $pasien = Pasien::orderBy('nama')->get();
        $dokter = User::where('role','dokter')->orderBy('name')->get();

        return view('kunjungan.create', compact('pasien','dokter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasien,id',
            'dokter_id' => 'required|exists:users,id',
            'satusehat_encounter_id' => 'nullable|string|max:100|unique:kunjungan,satusehat_encounter_id',
            'rajalranap'      => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            'waktu_masuk' => 'required|date_format:H:i',
            'keluhan_utama' => 'nullable|string',
        ]);

        // no_rawat urut harian
        $noRawat = $this->generateNoRawat();

        // gabungkan tanggal + jam
        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->tanggal_kunjungan.' '.$request->waktu_masuk)->format('Y-m-d H:i:s');

        Kunjungan::create([
            'no_rawat'         => $noRawat,
            'pasien_id'        => $request->pasien_id,
            'dokter_id'        => $request->dokter_id,
            'user_id'          => Auth::id(),
            'rumah_sakit_id'   => Auth::user()->rumah_sakit_id,
            'satusehat_encounter_id' => $request->satusehat_encounter_id,
            'rajalranap'             => $request->rajalranap,
            'tanggal_kunjungan'=> $request->tanggal_kunjungan,
            'waktu_masuk'      => $datetime,
            'keluhan_utama'    => $request->keluhan_utama,
            'status_pulang'    => 0,
        ]);

        return redirect()->route('kunjungan.index')->with('success','Kunjungan berhasil ditambahkan.');
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load(['pasien','dokter','user','soap']);
        return view('kunjungan.show', compact('kunjungan'));
    }

    public function edit(Kunjungan $kunjungan)
    {
        $pasien = Pasien::orderBy('nama')->get();
        $dokter = User::where('role','dokter')->orderBy('name')->get();

        $tanggal_default = optional($kunjungan->tanggal_kunjungan) ?: now()->toDateString();
        $jam_default = $kunjungan->waktu_masuk
            ? Carbon::parse($kunjungan->waktu_masuk)->format('H:i')
            : now()->format('H:i');

        return view('kunjungan.edit', compact('kunjungan','pasien','dokter','tanggal_default','jam_default'));
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasien,id',
            'dokter_id' => 'required|exists:users,id',
            'satusehat_encounter_id' => 'nullable|string|max:100|unique:kunjungan,satusehat_encounter_id,'.$kunjungan->id,
            'rajalranap'      => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            'waktu_masuk' => 'required|date_format:H:i',
            'keluhan_utama' => 'nullable|string',
            'status_pulang' => 'nullable|boolean',
        ]);

        $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->tanggal_kunjungan.' '.$request->waktu_masuk)->format('Y-m-d H:i:s');

        $kunjungan->update([
            'pasien_id'        => $request->pasien_id,
            'dokter_id'        => $request->dokter_id,
            'satusehat_encounter_id' => $request->satusehat_encounter_id,
            'rajalranap'             => $request->rajalranap,
            'tanggal_kunjungan'=> $request->tanggal_kunjungan,
            'waktu_masuk'      => $datetime,
            'keluhan_utama'    => $request->keluhan_utama,
            'status_pulang'    => $request->status_pulang ?? 0,
        ]);

        return redirect()->route('kunjungan.index')->with('success','Kunjungan diperbarui.');
    }

    public function destroy(Kunjungan $kunjungan)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        $kunjungan->delete();
        return redirect()->route('kunjungan.index')->with('success','Kunjungan dihapus.');
    }

    /**
     * Tandai pasien pulang
     */
    public function pulangkan(Kunjungan $kunjungan)
    {
        if ($kunjungan->status_pulang) {
            return back()->with('info','Pasien sudah dipulangkan.');
        }

        $kunjungan->update([
            'status_pulang' => 1,
            'waktu_pulang'  => now(),
        ]);

        return redirect()->route('kunjungan.index')->with('success','Pasien berhasil dipulangkan.');
    }

    /**
     * Cetak PDF sesuai filter aktif di index
     */
    public function cetak(Request $request)
{
    $query = Kunjungan::with(['pasien','dokter']);

    // Filter keyword pasien / no RM
    if ($request->filled('keyword')) {
        $query->whereHas('pasien', function($q) use ($request) {
            $q->where('nama','like','%'.$request->keyword.'%')
              ->orWhere('no_rkm_medis','like','%'.$request->keyword.'%');
        });
    }

    // Filter dokter
    if ($request->filled('dokter_id')) {
        $query->where('dokter_id', $request->dokter_id);
    }

    // Filter status
    if ($request->filled('status')) {
        $query->where('status_pulang', $request->status == 'pulang' ? 1 : 0);
    }

    // Filter tanggal
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('tanggal_kunjungan', [$request->start_date, $request->end_date]);
    }

    $kunjungan = $query->orderBy('tanggal_kunjungan','desc')->get();

    $pdf = Pdf::loadView('kunjungan.laporan', compact('kunjungan'))->setPaper('A4', 'portrait');
    return $pdf->stream('laporan-kunjungan.pdf');
}
}
