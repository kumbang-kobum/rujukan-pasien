<?php

namespace App\Http\Controllers;

use App\Models\BerkasMedis;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BerkasMedisController extends Controller
{
    public function create(Request $request)
    {
        $kunjungan = Kunjungan::findOrFail((int)$request->get('kunjungan_id'));
        $redirect  = $request->input('redirect');
        $soapId    = $request->input('soap_id');   // ⬅️
        return view('berkas.create', compact('kunjungan','redirect','soapId'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'kunjungan_id' => ['required','exists:kunjungan,id'],
            'soap_id'      => ['nullable','exists:soap,id'],
            'jenis'        => ['nullable','string','max:100'],
            'kategori'     => ['nullable','in:USG,LAB,LAIN'],
            'file'         => ['required','file','mimes:pdf,jpg,jpeg,png','max:4096'],
        ]);
    
        $file = $request->file('file');
        $path = $file->store('berkas','public');
    
        BerkasMedis::create([
            'kunjungan_id' => $request->kunjungan_id,
            'soap_id'      => $request->soap_id,     // ⬅️ kaitkan ke SOAP ini
            'uploader_id'  => auth()->id(),
            'jenis'        => $request->jenis,
            'kategori'     => $request->kategori,    // ⬅️ USG/LAB
            'nama_file'    => $file->getClientOriginalName(),
            'path'         => $path,
        ]);
    
        $redirect = $request->input('redirect');
        return redirect()->to($redirect ?: route('kunjungan.show',$request->kunjungan_id))
            ->with('success','Berkas berhasil diupload.');
    }
    
    public function edit(BerkasMedis $berka)
    {
        $redirect = request('redirect'); // <— opsional
        return view('berkas.edit', compact('berka','redirect'));
    }
    
    public function update(Request $request, BerkasMedis $berka)
    {
        $request->validate([
            'jenis' => ['nullable','string','max:100'],
            'file'  => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:2048'],
        ]);
    
        $data = ['jenis' => $request->jenis];
        if ($request->hasFile('file')) {
            if ($berka->path && \Storage::disk('public')->exists($berka->path)) {
                \Storage::disk('public')->delete($berka->path);
            }
            $file = $request->file('file');
            $data['nama_file'] = $file->getClientOriginalName();
            $data['path']      = $file->store('berkas','public');
        }
        $berka->update($data);
    
        $redirect = $request->input('redirect'); // <— tambahkan
        return redirect()->to($redirect ?: route('kunjungan.show',$berka->kunjungan_id))
            ->with('success','Berkas berhasil diperbarui.');
    }
    
    public function destroy(BerkasMedis $berka)
    {
        if ($berka->path && \Storage::disk('public')->exists($berka->path)) {
            \Storage::disk('public')->delete($berka->path);
        }
        $back = request('redirect'); // bisa datang dari query ?redirect=...
        $idKunj = $berka->kunjungan_id;
        $berka->delete();
    
        $redirect = request('redirect'); // <— tambahkan
        return redirect()->to($redirect ?: route('kunjungan.show',$berka->kunjungan_id))
            ->with('success','Berkas berhasil dihapus.');
    }

    // public function create(Request $request)
    // {
    //     $kunjungan = Kunjungan::findOrFail($request->get('kunjungan_id'));
    //     return view('berkas.create', compact('kunjungan'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'kunjungan_id' => 'required|exists:kunjungan,id',
    //         'jenis' => 'nullable|string|max:100',
    //         'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     $file = $request->file('file');
    //     $path = $file->store('berkas', 'public');

    //     BerkasMedis::create([
    //         'kunjungan_id' => $request->kunjungan_id,
    //         'uploader_id' => Auth::id(),
    //         'jenis' => $request->jenis,
    //         'nama_file' => $file->getClientOriginalName(),
    //         'path' => $path,
    //     ]);

    //     return redirect()->route('kunjungan.show', $request->kunjungan_id)
    //         ->with('success', 'Berkas berhasil diupload.');
    // }

    public function show(BerkasMedis $berka)
    {
        return view('berkas.show', compact('berka'));
    }

    // public function edit(BerkasMedis $berka)
    // {
    //     return view('berkas.edit', compact('berka'));
    // }

    // public function update(Request $request, BerkasMedis $berka)
    // {
    //     $request->validate([
    //         'jenis' => 'nullable|string|max:100',
    //         'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     $data = [
    //         'jenis' => $request->jenis,
    //     ];

    //     if ($request->hasFile('file')) {
    //         if ($berka->path && Storage::disk('public')->exists($berka->path)) {
    //             Storage::disk('public')->delete($berka->path);
    //         }
    //         $file = $request->file('file');
    //         $path = $file->store('berkas', 'public');
    //         $data['nama_file'] = $file->getClientOriginalName();
    //         $data['path'] = $path;
    //     }

    //     $berka->update($data);

    //     return redirect()->route('kunjungan.show', $berka->kunjungan_id)
    //         ->with('success', 'Berkas berhasil diperbarui.');
    // }

    // public function destroy(BerkasMedis $berka)
    // {
    //     if ($berka->path && Storage::disk('public')->exists($berka->path)) {
    //         Storage::disk('public')->delete($berka->path);
    //     }
    //     $kunjunganId = $berka->kunjungan_id;
    //     $berka->delete();

    //     return redirect()->route('kunjungan.show', $kunjunganId)
    //         ->with('success', 'Berkas berhasil dihapus.');
    // }
}