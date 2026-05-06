<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RumahSakit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Route controller ini dibatasi untuk super_admin dan admin_rs via routes.

    private function roleOptionsFor(User $actor): array
    {
        $roles = User::roleLabels();

        if (! $actor->isSuperAdmin()) {
            unset($roles[User::ROLE_SUPER_ADMIN]);
        }

        return $roles;
    }

    private function hospitalsFor(User $actor)
    {
        $query = RumahSakit::orderBy('nama');

        if ($actor->isAdminRs()) {
            $query->whereKey($actor->rumah_sakit_id);
        }

        return $query->get(['id', 'nama']);
    }

    private function authorizeManageUser(User $user): void
    {
        $actor = auth()->user();
        abort_unless($actor && $actor->canManageUsers(), 403);

        if ($actor->isSuperAdmin()) {
            return;
        }

        abort_unless(
            ! $user->isSuperAdmin()
            && (int) $user->rumah_sakit_id === (int) $actor->rumah_sakit_id,
            403
        );
    }

    public function index(Request $request)
    {
        $actor = $request->user();
        $q = trim((string) $request->get('q'));
        $filterRs = $actor->isSuperAdmin()
            ? $request->get('rumah_sakit_id')
            : $actor->rumah_sakit_id;

        $users = User::with('rumahSakit')
            ->when(! $actor->isSuperAdmin(), fn($x) =>
                $x->where('rumah_sakit_id', $actor->rumah_sakit_id)
                  ->where('role', '!=', User::ROLE_SUPER_ADMIN)
            )
            ->when($q !== '', fn($x) =>
                $x->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                })
            )
            ->when($actor->isSuperAdmin() && $filterRs, fn($x) =>
                $x->where('rumah_sakit_id', $filterRs)
            )
            ->orderBy('name')
            ->paginate(15)->withQueryString();

        $rsList = $this->hospitalsFor($actor);

        return view('users.index', compact('users','rsList','filterRs','q'));
    }

    public function create()
    {
        $actor = auth()->user();
        $rsList = $this->hospitalsFor($actor);
        $roleOptions = $this->roleOptionsFor($actor);

        return view('users.create', compact('rsList', 'roleOptions'));
    }

    public function store(Request $request)
    {
        $actor = $request->user();
        $roleOptions = $this->roleOptionsFor($actor);
        $allowedHospitalIds = $this->hospitalsFor($actor)->pluck('id')->map(fn ($id) => (string) $id)->all();

        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'password'        => 'required|string|min:6|confirmed',
            'role'            => ['required', Rule::in(array_keys($roleOptions))],
            'rumah_sakit_id'  => [
                Rule::requiredIf(fn () => $request->input('role') !== User::ROLE_SUPER_ADMIN),
                'nullable',
                Rule::in($allowedHospitalIds),
            ],
        ]);

        if ($actor->isAdminRs()) {
            $data['rumah_sakit_id'] = $actor->rumah_sakit_id;
        }
        if ($data['role'] === User::ROLE_SUPER_ADMIN) {
            $data['rumah_sakit_id'] = $data['rumah_sakit_id'] ?? null;
        }

        User::create([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'role'            => $data['role'],
            'rumah_sakit_id'  => $data['rumah_sakit_id'] ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorizeManageUser($user);

        $actor = auth()->user();
        $rsList = $this->hospitalsFor($actor);
        $roleOptions = $this->roleOptionsFor($actor);

        return view('users.edit', compact('user','rsList', 'roleOptions'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeManageUser($user);

        $actor = $request->user();
        $roleOptions = $this->roleOptionsFor($actor);
        $allowedHospitalIds = $this->hospitalsFor($actor)->pluck('id')->map(fn ($id) => (string) $id)->all();

        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,'.$user->id,
            'role'            => ['required', Rule::in(array_keys($roleOptions))],
            'rumah_sakit_id'  => [
                Rule::requiredIf(fn () => $request->input('role') !== User::ROLE_SUPER_ADMIN),
                'nullable',
                Rule::in($allowedHospitalIds),
            ],
            'password'        => 'nullable|string|min:6|confirmed',
        ]);

        if ($actor->isAdminRs()) {
            $data['rumah_sakit_id'] = $actor->rumah_sakit_id;
        }
        if ($data['role'] === User::ROLE_SUPER_ADMIN) {
            $data['rumah_sakit_id'] = $data['rumah_sakit_id'] ?? null;
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorizeManageUser($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->isSuperAdmin() && ! User::where('role', User::ROLE_SUPER_ADMIN)->where('id', '!=', $user->id)->exists()) {
            return back()->with('error', 'Minimal harus ada satu akun Super Admin.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // AJAX: ambil dokter berdasarkan RS tujuan
    public function dokterByRs(RumahSakit $rs)
    {
        return User::where('role', User::ROLE_DOKTER)
            ->where('rumah_sakit_id', $rs->id)
            ->orderBy('name')
            ->get(['id','name']);
    }
}
