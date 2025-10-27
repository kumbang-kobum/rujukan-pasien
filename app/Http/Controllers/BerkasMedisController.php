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
        $kunjungan = Kunjungan::findOrFail($request->get('kunjungan_id'));
        return view('berkas.create', compact('kunjungan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kunjungan_id' => 'required|exists:kunjungan,id',
            'jenis' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('berkas', 'public');

        BerkasMedis::create([
            'kunjungan_id' => $request->kunjungan_id,
            'uploader_id' => Auth::id(),
            'jenis' => $request->jenis,
            'nama_file' => $file->getClientOriginalName(),
            'path' => $path,
        ]);

        return redirect()->route('kunjungan.show', $request->kunjungan_id)
            ->with('success', 'Berkas berhasil diupload.');
    }

    public function show(BerkasMedis $berka)
    {
        return view('berkas.show', compact('berka'));
    }

    public function edit(BerkasMedis $berka)
    {
        return view('berkas.edit', compact('berka'));
    }

    public function update(Request $request, BerkasMedis $berka)
    {
        $request->validate([
            'jenis' => 'nullable|string|max:100',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'jenis' => $request->jenis,
        ];

        if ($request->hasFile('file')) {
            if ($berka->path && Storage::disk('public')->exists($berka->path)) {
                Storage::disk('public')->delete($berka->path);
            }
            $file = $request->file('file');
            $path = $file->store('berkas', 'public');
            $data['nama_file'] = $file->getClientOriginalName();
            $data['path'] = $path;
        }

        $berka->update($data);

        return redirect()->route('kunjungan.show', $berka->kunjungan_id)
            ->with('success', 'Berkas berhasil diperbarui.');
    }

    public function destroy(BerkasMedis $berka)
    {
        if ($berka->path && Storage::disk('public')->exists($berka->path)) {
            Storage::disk('public')->delete($berka->path);
        }
        $kunjunganId = $berka->kunjungan_id;
        $berka->delete();

        return redirect()->route('kunjungan.show', $kunjunganId)
            ->with('success', 'Berkas berhasil dihapus.');
    }
}