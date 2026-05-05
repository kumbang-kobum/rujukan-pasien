@extends('layouts.app')
@section('title','Tambah Pengguna')
@section('content')

<div class="card shadow-sm">
  <div class="card-header bg-success text-white">
    <i class="fas fa-user-plus"></i> Tambah User Baru
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('users.store') }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Practitioner IHS</label>
          <input type="text" name="practitioner_ihs_number" class="form-control" value="{{ old('practitioner_ihs_number') }}">
          @error('practitioner_ihs_number') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">PractitionerRole ID</label>
          <input type="text" name="satusehat_practitioner_role_id" class="form-control" value="{{ old('satusehat_practitioner_role_id') }}">
          @error('satusehat_practitioner_role_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Spesialisasi</label>
          <input type="text" name="spesialisasi" class="form-control" value="{{ old('spesialisasi') }}">
          @error('spesialisasi') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Password</label>
          <div class="position-relative">
            <input type="password" name="password" id="new_password"
                  class="form-control pe-5" required autocomplete="new-password">
            <button type="button"
                    class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 px-2
                          text-muted bg-transparent border-0 toggle-eye"
                    data-target="#new_password" aria-label="Tampilkan password" aria-pressed="false">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Konfirmasi Password</label>
          <div class="position-relative">
            <input type="password" name="password_confirmation" id="new_password_confirmation"
                  class="form-control pe-5" required autocomplete="new-password">
            <button type="button"
                    class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 px-2
                          text-muted bg-transparent border-0 toggle-eye"
                    data-target="#new_password_confirmation" aria-label="Tampilkan password" aria-pressed="false">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Rumah Sakit</label>
        <select name="rumah_sakit_id" class="form-select @error('rumah_sakit_id') is-invalid @enderror" required>
          <option value="">Pilih RS…</option>
          @foreach($rsList as $rs)
            <option value="{{ $rs->id }}" {{ old('rumah_sakit_id')==$rs->id ? 'selected' : '' }}>
              {{ $rs->nama }}
            </option>
          @endforeach
        </select>
        @error('rumah_sakit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="" disabled selected>Pilih...</option>
          <option value="admin">Admin</option>
          <option value="dokter">Dokter</option>
          <option value="perawat">Perawat</option>
        </select>
        @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <button class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
