@extends('layouts.app')
@section('title','Kelola Pengguna')
@section('content')

<div class="card shadow-sm">
  <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-users"></i> Daftar Pengguna</span>
    <a href="{{ route('users.create') }}" class="btn btn-sm btn-success">
      <i class="fas fa-plus"></i> Tambah User
    </a>
  </div>

  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form class="row g-2 mb-3" method="get">
      <div class="{{ auth()->user()->isSuperAdmin() ? 'col-md-4' : 'col-md-8' }}">
        <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="Cari nama/email…">
      </div>
      @if(auth()->user()->isSuperAdmin())
        <div class="col-md-4">
          <select name="rumah_sakit_id" class="form-select">
            <option value="">— Semua RS —</option>
            @foreach($rsList as $rs)
              <option value="{{ $rs->id }}" {{ (string)$filterRs === (string)$rs->id ? 'selected' : '' }}>{{ $rs->nama }}</option>
            @endforeach
          </select>
        </div>
      @endif
      <div class="col-md-4">
        <button class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
      </div>
    </form>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Rumah Sakit</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $u)
          <tr>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td><span class="badge bg-secondary">{{ $u->role_label }}</span></td>
            <td>{{ $u->rumahSakit->nama ?? '-' }}</td>
            <td class="text-nowrap">
              <a href="{{ route('users.edit',$u->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('users.destroy',$u->id) }}" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center">Belum ada pengguna.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
