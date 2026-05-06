<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:super_admin,admin_rs']);
    }

    // (Opsional) Halaman ringkas admin
    public function index()
    {
        return view('admin.dashboard');
    }

    // Form ubah password admin
    public function editPassword()
    {
        return view('admin.password');
    }

    // Proses ubah password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required','current_password'],   // validasi bawaan Laravel
            'password'         => ['required','confirmed', Password::min(6)],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success','Password berhasil diubah.');
    }
}
