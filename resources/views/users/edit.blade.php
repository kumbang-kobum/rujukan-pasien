@extends('layouts.app')
@section('title','Edit Pengguna')
@section('content')

<div class="card shadow-sm">
  <div class="card-header bg-warning">
    <i class="fas fa-user-edit"></i> Edit User
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('users.update', $user->id) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Practitioner IHS</label>
          <input type="text" name="practitioner_ihs_number" class="form-control" value="{{ old('practitioner_ihs_number', $user->practitioner_ihs_number) }}">
          @error('practitioner_ihs_number') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">PractitionerRole ID</label>
          <input type="text" name="satusehat_practitioner_role_id" class="form-control" value="{{ old('satusehat_practitioner_role_id', $user->satusehat_practitioner_role_id) }}">
          @error('satusehat_practitioner_role_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Spesialisasi</label>
          <input type="text" name="spesialisasi" class="form-control" value="{{ old('spesialisasi', $user->spesialisasi) }}">
          @error('spesialisasi') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Password Baru (opsional)</label>
          <div class="position-relative">
            <input type="password" name="password" id="password"
                  class="form-control pe-5" autocomplete="new-password">
            <button type="button"
                    class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 px-2
                          text-muted bg-transparent border-0 toggle-eye"
                    data-target="#password" aria-label="Tampilkan password" aria-pressed="false">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Konfirmasi Password</label>
          <div class="position-relative">
            <input type="password" name="password_confirmation" id="password_confirmation"
                  class="form-control pe-5" autocomplete="new-password">
            <button type="button"
                    class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 px-2
                          text-muted bg-transparent border-0 toggle-eye"
                    data-target="#password_confirmation" aria-label="Tampilkan password" aria-pressed="false">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Rumah Sakit</label>
        <select name="rumah_sakit_id" class="form-select @error('rumah_sakit_id') is-invalid @enderror" required>
          @foreach($rsList as $rs)
            <option value="{{ $rs->id }}" {{ (int)old('rumah_sakit_id', $user->rumah_sakit_id) === (int)$rs->id ? 'selected' : '' }}>
              {{ $rs->nama }}
            </option>
          @endforeach
        </select>
        @error('rumah_sakit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="dokter" {{ $user->role == 'dokter' ? 'selected' : '' }}>Dokter</option>
          <option value="perawat" {{ $user->role == 'perawat' ? 'selected' : '' }}>Perawat</option>
        </select>
      </div>

      <button class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
      <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
