<!-- Tombol pemicu modal -->
<button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteUser">
  <i class="fas fa-user-times me-1"></i> Hapus Akun
</button>

<!-- Modal Bootstrap -->
<div class="modal fade" id="modalDeleteUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
      @csrf
      @method('delete')

      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p class="mb-3">
          Setelah dihapus, semua data akun akan hilang permanen. Masukkan password untuk konfirmasi.
        </p>
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password','userDeletion') is-invalid @enderror">
        @error('password','userDeletion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">Hapus Akun</button>
      </div>
    </form>
  </div>
</div>
