@extends('layouts.app')
@section('title','Ubah Password (Admin)')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">Ubah Password</div>
  <div class="card-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <form method="POST" action="{{ route('admin.password.update') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Password Saat Ini</label>
        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>
      <button class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
@endsection
