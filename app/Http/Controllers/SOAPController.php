<?php

namespace App\Http\Controllers;

use App\Models\SOAP;
use App\Models\Kunjungan;
use App\Models\BerkasMedis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class SOAPController extends Controller
{
    private function authorizeView(SOAP $soap): void
    {
        abort_unless(
            SOAP::query()->visibleTo(Auth::user())->whereKey($soap->id)->exists(),
            403
        );
    }

    private function visibleKunjunganQuery()
    {
        return Kunjungan::query()->visibleTo(Auth::user());
    }

    public function index(Request $request)
    {
        $q = SOAP::query()
            ->visibleTo($request->user())
            ->with(['kunjungan.pasien','kunjungan.dokter','user']);

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
        $users = User::where('rumah_sakit_id', $request->user()->rumah_sakit_id)
            ->orderBy('name')
            ->get(['id','name']);

        return view('soap.index', ['soap' => $soap, 'users' => $users]);
    }

    public function cetak(SOAP $soap)
    {
        $this->authorizeView($soap);

        $soap->load(['kunjungan.pasien','kunjungan.dokter','user']);

        $pdf = PDF::loadView('soap.cetak', compact('soap'))
            ->setPaper('A4','portrait');

        return $pdf->stream('SOAP-'.$soap->id.'.pdf');
    }

    public function create()
    {
        $kunjungan = $this->visibleKunjunganQuery()
            ->with('pasien')
            ->whereDate('tanggal_kunjungan', now()->toDateString())
            ->where('status_pulang', 0)
            ->get();

        return view('soap.create', compact('kunjungan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kunjungan_id'              => 'required|exists:kunjungan,id',
            'subjektif'                 => 'nullable|string',
            'objektif'                  => 'nullable|string',
            'assessment'                => 'nullable|string',
            'plan'                      => 'nullable|string',
            'advice'                    => 'nullable|string',
            'td_sys'                    => 'nullable|integer|min:40|max:300',
            'td_dia'                    => 'nullable|integer|min:20|max:200',
            'map'                       => 'nullable|integer|min:20|max:200',
    
            // --- per baris ---
            'lampiran_file'             => 'array',
            'lampiran_file.*'           => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
            'lampiran_kategori'         => 'array',
            'lampiran_kategori.*'       => 'nullable|in:USG,LAB,LAIN',
        ]);

        $kunjungan = $this->visibleKunjunganQuery()->findOrFail($request->integer('kunjungan_id'));
    
        // Hitung MAP jika diisi
        $calcMap = null;
        if ($request->filled(['td_sys','td_dia'])) {
            $calcMap = (int) round($request->td_dia + ($request->td_sys - $request->td_dia) / 3);
        }
    
        // Buat SOAP & simpan ke variabel
        $soap = SOAP::create([
            'kunjungan_id' => $kunjungan->id,
            'user_id'      => Auth::id(),
            'subjektif'    => $request->subjektif,
            'objektif'     => $request->objektif,
            'assessment'   => $request->assessment,
            'plan'         => $request->plan,
            'advice'       => $request->advice,
            'td_sys'       => $request->td_sys,
            'td_dia'       => $request->td_dia,
            'map'          => $request->map ?? $calcMap,
        ]);
    
        // ==== SIMPAN LAMPIRAN PER BARIS ====
        $files = $request->file('lampiran_file', []);
        $kats  = $request->input('lampiran_kategori', []);
    
        // Pastikan array keduanya sejajar
        foreach ($files as $i => $file) {
            if (!$file) continue;
            if (method_exists($file, 'isValid') && !$file->isValid()) continue;
    
            $path = $file->store('berkas', 'public');
    
            $soap->berkas()->create([
                'kunjungan_id' => $soap->kunjungan_id,                 // penting agar tidak error default value
                'kategori'     => $kats[$i] ?? 'LAIN',                 // per indeks
                'nama_file'    => $file->getClientOriginalName(),
                'path'         => $path,
                'uploader_id'  => Auth::id(), 
                // Hapus baris ini jika kolom 'mime' tidak ada di tabel
                // 'mime'         => $file->getClientMimeType(),
                // 'user_id'      => auth()->id(),
            ]);
        }
    
        return redirect()->route('soap.index')->with('success', 'SOAP berhasil ditambahkan.');
    }

    public function show(SOAP $soap)
    {
        $this->authorizeView($soap);

        $soap->load(['kunjungan.pasien','kunjungan.dokter','user']);
    
        $berkasKunjungan = $soap->berkas()->with('uploader')->latest()->get();
    
        return view('soap.show', compact('soap','berkasKunjungan'));
    }

    public function edit(SOAP $soap)
    {
        $this->authorizeView($soap);

        $kunjungan = $this->visibleKunjunganQuery()
            ->with('pasien')
            ->whereDate('tanggal_kunjungan', now()->toDateString())
            ->where('status_pulang', 0)
            ->get();
    
        // HANYA berkas untuk kunjungan ini
        $soap->load(['kunjungan.pasien','kunjungan.dokter','user']);
    
        $berkasKunjungan = $soap->berkas()->with('uploader')->latest()->get();
    
        return view('soap.edit', compact('soap', 'kunjungan', 'berkasKunjungan'));
    }


    public function update(Request $request, SOAP $soap)
    {
        $this->authorizeView($soap);

        $request->validate([
            'kunjungan_id' => 'required|exists:kunjungan,id',
            'subjektif'    => 'nullable|string',
            'objektif'     => 'nullable|string',
            'assessment'   => 'nullable|string',
            'plan'         => 'nullable|string',
            'advice'       => 'nullable|string',
            'td_sys'       => 'nullable|integer|min:40|max:300',
            'td_dia'       => 'nullable|integer|min:20|max:200',
            'map'          => 'nullable|integer|min:20|max:200',
    
            // edit lampiran lama
            'berkas_lama'               => 'array',
            'berkas_lama.*.kategori'    => 'nullable|in:USG,LAB,LAIN',
            'berkas_lama.*._delete'     => 'nullable|boolean',
            'berkas_lama.*.file'        => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
    
            // tambah lampiran baru (opsional)
            'lampiran_file'             => 'array',
            'lampiran_file.*'           => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
            'lampiran_kategori'         => 'array',
            'lampiran_kategori.*'       => 'nullable|in:USG,LAB,LAIN',
        ]);

        $kunjungan = $this->visibleKunjunganQuery()->findOrFail($request->integer('kunjungan_id'));
    
        // hitung MAP jika perlu
        $calcMap = null;
        if ($request->filled(['td_sys','td_dia'])) {
            $calcMap = (int) round($request->td_dia + ($request->td_sys - $request->td_dia) / 3);
        }
    
        // update data SOAP
        $soap->update([
            'kunjungan_id' => $kunjungan->id,
            'subjektif'    => $request->subjektif,
            'objektif'     => $request->objektif,
            'assessment'   => $request->assessment,
            'plan'         => $request->plan,
            'advice'       => $request->advice,
            'td_sys'       => $request->td_sys,
            'td_dia'       => $request->td_dia,
            'map'          => $request->map ?? $calcMap,
        ]);
    
        /* ====== PROSES LAMPIRAN LAMA ====== */
        foreach ((array)$request->input('berkas_lama', []) as $id => $row) {
            $bk = $soap->berkas()->find($id);
            if (!$bk) continue;
    
            // hapus
            if (!empty($row['_delete'])) {
                Storage::disk('public')->delete($bk->path);
                $bk->delete();
                continue;
            }
    
            // update kategori
            if (!empty($row['kategori']) && in_array($row['kategori'], ['USG','LAB','LAIN'])) {
                $bk->kategori = $row['kategori'];
            }
    
            // replace file
            if ($request->hasFile("berkas_lama.$id.file")) {
                $file    = $request->file("berkas_lama.$id.file");
                $newPath = $file->store('berkas', 'public');
    
                // hapus file lama
                Storage::disk('public')->delete($bk->path);
    
                $bk->path      = $newPath;
                $bk->nama_file = $file->getClientOriginalName();
                $bk->uploader_id = Auth::id();
                // Jika tabel punya kolom 'mime' / 'user_id', aktifkan 2 baris ini:
                // $bk->mime      = $file->getClientMimeType();
                // $bk->user_id   = auth()->id();
            }
    
            // jaga sinkron kunjungan
            $bk->kunjungan_id = $soap->kunjungan_id;
            $bk->save();
        }
    
        /* ====== TAMBAH LAMPIRAN BARU (opsional) ====== */
        $files = $request->file('lampiran_file', []);
        $kats  = $request->input('lampiran_kategori', []);
        foreach ((array)$files as $i => $file) {
            if (!$file) continue;
    
            $path = $file->store('berkas', 'public');
            $soap->berkas()->create([
                'kunjungan_id' => $soap->kunjungan_id,
                'kategori'     => $kats[$i] ?? 'LAIN',
                'nama_file'    => $file->getClientOriginalName(),
                'path'         => $path,
                'uploader_id'  => Auth::id(), 
                // aktifkan jika kolom tersedia:
                // 'mime'      => $file->getClientMimeType(),
                // 'user_id'   => auth()->id(),
            ]);
        }
    
        return redirect()->route('soap.index')->with('success','SOAP berhasil diperbarui.');
    }

    public function destroy(SOAP $soap)
    {
        $this->authorizeView($soap);
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        $soap->delete();
        return redirect()->route('soap.index')->with('success','SOAP dihapus.');
    }
}
