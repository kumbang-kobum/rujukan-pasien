<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RumahSakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // SEMUA route controller ini sudah dibatasi middleware role:admin via routes

    public function index(Request $request)
    {
        $q = $request->get('q');
        $filterRs = $request->get('rumah_sakit_id');

        $users = User::with('rumahSakit')
            ->when($q, fn($x) =>
                $x->where('name','like',"%$q%")
                  ->orWhere('email','like',"%$q%")
            )
            ->when($filterRs, fn($x) => $x->where('rumah_sakit_id', $filterRs))
            ->orderBy('name')
            ->paginate(15)->withQueryString();

        $rsList = RumahSakit::orderBy('nama')->get(['id','nama']);

        return view('users.index', compact('users','rsList','filterRs','q'));
    }

    public function create()
    {
        $rsList = RumahSakit::orderBy('nama')->get(['id','nama']);
        return view('users.create', compact('rsList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'practitioner_ihs_number' => 'nullable|string|max:100|unique:users,practitioner_ihs_number',
            'satusehat_practitioner_role_id' => 'nullable|string|max:100|unique:users,satusehat_practitioner_role_id',
            'spesialisasi' => 'nullable|string|max:255',
            'password'        => 'required|string|min:6|confirmed',
            'role'            => 'required|in:admin,dokter,perawat',
            'rumah_sakit_id'  => 'required|exists:rumah_sakit,id',
        ]);

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'practitioner_ihs_number' => $request->practitioner_ihs_number,
            'satusehat_practitioner_role_id' => $request->satusehat_practitioner_role_id,
            'spesialisasi' => $request->spesialisasi,
            'password'        => Hash::make($request->password),
            'role'            => $request->role,
            'rumah_sakit_id'  => $request->rumah_sakit_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $rsList = RumahSakit::orderBy('nama')->get(['id','nama']);
        return view('users.edit', compact('user','rsList'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,'.$user->id,
            'practitioner_ihs_number' => 'nullable|string|max:100|unique:users,practitioner_ihs_number,'.$user->id,
            'satusehat_practitioner_role_id' => 'nullable|string|max:100|unique:users,satusehat_practitioner_role_id,'.$user->id,
            'spesialisasi' => 'nullable|string|max:255',
            'role'            => 'required|in:admin,dokter,perawat',
            'rumah_sakit_id'  => 'required|exists:rumah_sakit,id',
            'password'        => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only([
            'name',
            'email',
            'practitioner_ihs_number',
            'satusehat_practitioner_role_id',
            'spesialisasi',
            'role',
            'rumah_sakit_id',
        ]);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // AJAX: ambil dokter berdasarkan RS tujuan
    public function dokterByRs(RumahSakit $rs)
    {
        return User::where('role','dokter')
            ->where('rumah_sakit_id', $rs->id)
            ->orderBy('name')
            ->get(['id','name']);
    }
}
