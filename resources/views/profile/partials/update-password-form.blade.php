@if (session('status') === 'password-updated')
  <div class="alert alert-success">Password berhasil diperbarui.</div>
@endif

<form method="post" action="{{ route('password.update') }}">
  @csrf
  @method('put')

  <div class="mb-3">
    <label class="form-label">Password Saat Ini</label>
    <div class="input-group">
      <input type="password" name="current_password"
             class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
             autocomplete="current-password" id="pwd-current">
      <button class="btn btn-outline-secondary toggle-eye" type="button" data-target="#pwd-current">
        <i class="fa-solid fa-eye"></i>
      </button>
      @error('current_password', 'updatePassword') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">Password Baru</label>
    <div class="input-group">
      <input type="password" name="password"
             class="form-control @error('password', 'updatePassword') is-invalid @enderror"
             autocomplete="new-password" id="pwd-new">
      <button class="btn btn-outline-secondary toggle-eye" type="button" data-target="#pwd-new">
        <i class="fa-solid fa-eye"></i>
      </button>
      @error('password', 'updatePassword') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">Konfirmasi Password Baru</label>
    <div class="input-group">
      <input type="password" name="password_confirmation"
             class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
             autocomplete="new-password" id="pwd-confirm">
      <button class="btn btn-outline-secondary toggle-eye" type="button" data-target="#pwd-confirm">
        <i class="fa-solid fa-eye"></i>
      </button>
      @error('password_confirmation', 'updatePassword') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
  </div>

  <button class="btn btn-secondary">
    <i class="fas fa-save me-1"></i> Simpan
  </button>
</form>
