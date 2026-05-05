<?php

namespace App\Http\Controllers;

use App\Models\RumahSakit;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class RumahSakitController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $items = RumahSakit::when($q, fn($x) =>
                $x->where('nama','like',"%$q%")
                  ->orWhere('alamat','like',"%$q%")
                  ->orWhere('telepon','like',"%$q%")
            )
            ->orderBy('nama')
            ->paginate(10)->withQueryString();

        return view('rumahsakit.index', compact('items','q'));
    }

    public function create()
    {
        $rs = new RumahSakit();
        return view('rumahsakit.create', compact('rs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'    => ['required','string','max:100','unique:rumah_sakit,nama'],
            'alamat'  => ['nullable','string','max:255'],
            'telepon' => ['nullable','string','max:50'],
        ]);

        RumahSakit::create($data);
        return redirect()->route('rumahsakit.index')->with('success','Rumah sakit ditambahkan.');
    }

    public function edit(RumahSakit $rumahsakit)
    {
        $rs = $rumahsakit;
        return view('rumahsakit.edit', compact('rs'));
    }

    public function update(Request $request, RumahSakit $rumahsakit)
    {
        $data = $request->validate([
            'nama'    => ['required','string','max:100','unique:rumah_sakit,nama,'.$rumahsakit->id],
            'alamat'  => ['nullable','string','max:255'],
            'telepon' => ['nullable','string','max:50'],
        ]);

        $rumahsakit->update($data);
        return redirect()->route('rumahsakit.index')->with('success','Rumah sakit diperbarui.');
    }

    public function destroy(RumahSakit $rumahsakit)
    {
        try {
            if ($rumahsakit->users()->exists()) {
                return back()->with('error','Tidak bisa hapus: masih ada pengguna terkait.');
            }
            $rumahsakit->delete();
            return back()->with('success','Rumah sakit dihapus.');
        } catch (QueryException $e) {
            return back()->with('error','Gagal hapus (masih direferensikan).');
        }
    }
}
