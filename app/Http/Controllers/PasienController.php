<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index(Request $request)
    {
          $q = Pasien::query();

        // Keyword: nama / no_rkm_medis / nik
        if ($kw = trim($request->input('keyword', ''))) {
            $q->where(function($sub) use ($kw){
                $sub->where('nama', 'like', "%{$kw}%")
                    ->orWhere('no_rkm_medis', 'like', "%{$kw}%")
                    ->orWhere('nik', 'like', "%{$kw}%");
            });
        }

        // Jenis kelamin
        if (in_array($request->input('jk'), ['L','P'])) {
            $q->where('jenis_kelamin', $request->input('jk'));
        }

        // Rentang tanggal lahir (ganti 'tanggal_lahir' -> 'tgl_lahir' kalau perlu)
        $from = $request->input('tgl_lahir_from');
        $to   = $request->input('tgl_lahir_to');

        if ($from) {
            $q->whereDate('tanggal_lahir', '>=', $from);
        }
        if ($to) {
            $q->whereDate('tanggal_lahir', '<=', $to);
        }

        // Sorting aman
        $allowedSort = ['nama','no_rkm_medis','tanggal_lahir','created_at'];
        $sortBy  = in_array($request->input('sort_by'), $allowedSort) ? $request->input('sort_by') : 'nama';
        $sortDir = $request->input('sort_dir') === 'desc' ? 'desc' : 'asc';

        $q->orderBy($sortBy, $sortDir);

        // Per halaman
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10,25,50,100])) $perPage = 10;

        $pasien = $q->paginate($perPage)->withQueryString();
        return view('pasien.index', compact('pasien'));
    }

    public function create()
    {
        // Ambil no RM terakhir
        $last = Pasien::orderBy('id', 'desc')->first();
        $nextNo = $last ? intval($last->no_rkm_medis) + 1 : 1;

        // Format jadi 6 digit: 000001, 000002, dst
        $no_rkm_medis = str_pad($nextNo, 6, '0', STR_PAD_LEFT);

        return view('pasien.create', compact('no_rkm_medis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'           => 'required|unique:pasien',
            'patient_ihs_number' => 'nullable|string|max:100|unique:pasien,patient_ihs_number',
            'nama'          => 'required|string|max:255',
            'tempat_lahir'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string|max:500',
            'telepon'       => 'nullable|string|max:20',
        ]);

        Pasien::create([
            'no_rkm_medis'  => $request->no_rkm_medis,
            'nik'           => $request->nik,
            'patient_ihs_number' => $request->patient_ihs_number,
            'nama'          => $request->nama,
            'tempat_lahir'  => $request->tempat_lahir, // <<< WAJIB ADA
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'telepon'       => $request->telepon,
        ]);

        return redirect()->route('pasien.index')
            ->with('success', '✅ Pasien berhasil ditambahkan.');
    }

    public function show(Pasien $pasien)
    {
        return view('pasien.show', compact('pasien'));
    }

    public function edit(Pasien $pasien)
    {
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nik'          => 'required|unique:pasien,nik,' . $pasien->id,
            'patient_ihs_number' => 'nullable|string|max:100|unique:pasien,patient_ihs_number,' . $pasien->id,
            'nama'         => 'required|string|max:255',
            'tanggal_lahir'=> 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'jenis_kelamin'=> 'required|in:L,P',
            'alamat'       => 'required|string|max:500',
            'telepon'      => 'nullable|string|max:20',
        ]);

        $pasien->update($request->all());
        return redirect()->route('pasien.index')->with('success', '✅ Pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        $pasien->delete();
        return redirect()->route('pasien.index')->with('success', '🗑️ Pasien berhasil dihapus.');
    }
}
