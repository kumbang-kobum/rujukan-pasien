<?php

namespace App\Http\Controllers;

use App\Models\SOAP;
use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class SOAPController extends Controller
{
    public function index(Request $request)
    {
        $q = SOAP::query()->with(['kunjungan.pasien','kunjungan.dokter','user']);

        // Keyword: cari di no_rawat, no_rkm_medis, nama pasien, serta isi SOAP
        if ($kw = trim($request->input('keyword', ''))) {
            $q->where(function($w) use ($kw) {
                $w->where('subjektif','like',"%{$kw}%")
                  ->orWhere('objektif','like',"%{$kw}%")
                  ->orWhere('assessment','like',"%{$kw}%")
                  ->orWhere('plan','like',"%{$kw}%")
                  ->orWhereHas('kunjungan', function($wk) use ($kw){
                      $wk->where('no_rawat','like',"%{$kw}%")
                         ->orWhereHas('pasien', function($wp) use ($kw){
                             $wp->where('no_rkm_medis','like',"%{$kw}%")
                                ->orWhere('nama','like',"%{$kw}%");
                         });
                  });
            });
        }

        // Filter User Input
        if ($userId = $request->input('user_id')) {
            $q->where('user_id', $userId);
        }

        // Rentang tanggal dibuat (created_at)
        if ($from = $request->input('created_from')) {
            $q->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('created_to')) {
            $q->whereDate('created_at', '<=', $to);
        }

        // Sorting aman
        $allowedSort = ['created_at','id'];
        $sortBy  = in_array($request->input('sort_by'), $allowedSort) ? $request->input('sort_by') : 'created_at';
        $sortDir = $request->input('sort_dir') === 'asc' ? 'asc' : 'desc';
        $q->orderBy($sortBy, $sortDir);

        // Per halaman
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10,25,50,100])) $perPage = 10;

        $soap = $q->paginate($perPage)->withQueryString();

        // Untuk dropdown "User Input"
        $users = User::orderBy('name')->get(['id','name']);

        return view('soap.index', ['soap' => $soap, 'users' => $users]);
    }

    public function cetak(SOAP $soap)
    {
        $soap->load(['kunjungan.pasien','kunjungan.dokter','user']);

        $pdf = PDF::loadView('soap.cetak', compact('soap'))
            ->setPaper('A4','portrait');

        return $pdf->stream('SOAP-'.$soap->id.'.pdf');
    }

    public function create()
    {
        $kunjungan = Kunjungan::with('pasien')
            ->whereDate('tanggal_kunjungan', now()->toDateString())
            ->where('status_pulang', 0)
            ->get();

        return view('soap.create', compact('kunjungan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kunjungan_id' => 'required|exists:kunjungan,id',
            'subjektif'    => 'nullable|string',
            'objektif'     => 'nullable|string',
            'assessment'   => 'nullable|string',
            'plan'         => 'nullable|string',
        ]);

        SOAP::create([
            'kunjungan_id' => $request->kunjungan_id,
            'user_id'      => Auth::id(),
            'subjektif'    => $request->subjektif,
            'objektif'     => $request->objektif,
            'assessment'   => $request->assessment,
            'plan'         => $request->plan,
        ]);

        return redirect()->route('soap.index')->with('success','SOAP berhasil ditambahkan.');
    }

    public function show(SOAP $soap)
    {
        $soap->load(['kunjungan.pasien','kunjungan.dokter','user']);
        return view('soap.show', compact('soap'));
    }

    public function edit(SOAP $soap)
    {
        $kunjungan = Kunjungan::with('pasien')
            ->whereDate('tanggal_kunjungan', now()->toDateString())
            ->where('status_pulang', 0)
            ->get();

        return view('soap.edit', compact('soap','kunjungan'));
    }

    public function update(Request $request, SOAP $soap)
    {
        $request->validate([
            'kunjungan_id' => 'required|exists:kunjungan,id',
            'subjektif'    => 'nullable|string',
            'objektif'     => 'nullable|string',
            'assessment'   => 'nullable|string',
            'plan'         => 'nullable|string',
        ]);

        $soap->update([
            'kunjungan_id' => $request->kunjungan_id,
            'subjektif'    => $request->subjektif,
            'objektif'     => $request->objektif,
            'assessment'   => $request->assessment,
            'plan'         => $request->plan,
        ]);

        return redirect()->route('soap.index')->with('success','SOAP berhasil diperbarui.');
    }

    public function destroy(SOAP $soap)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        $soap->delete();
        return redirect()->route('soap.index')->with('success','SOAP dihapus.');
    }
}