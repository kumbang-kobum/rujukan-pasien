@if (session('status') === 'profile-updated')
  <div class="alert alert-success">Profil berhasil disimpan.</div>
@endif

<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
  @csrf
  @method('patch')

  {{-- Avatar preview --}}
  <div class="mb-3 d-flex align-items-center gap-3">
    <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-circle" style="width:64px;height:64px;object-fit:cover">
    <div class="flex-grow-1">
      <label class="form-label">Foto Profil (opsional)</label>
      <input type="file" name="avatar" accept="image/*"
             class="form-control @error('avatar') is-invalid @enderror">
      <small class="text-muted">.jpg/.png/.webp, maks 2 MB</small>
      @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $user->email) }}" required autocomplete="username">
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  {{-- (verifikasi email) tetap seperti milikmu --}}

  <button id="saveProfileBtn" class="btn btn-primary" type="submit">
    <span id="saveProfileSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
    <span class="btn-text">Simpan</span>
  </button>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('profileForm');
  const btn = document.getElementById('saveProfileBtn');
  const spn = document.getElementById('saveProfileSpinner');
  const txt = btn.querySelector('.btn-text');

  form.addEventListener('submit', function (e) {
    if (form.dataset.submitted === 'true') { e.preventDefault(); return; }
    form.dataset.submitted = 'true';

    spn.classList.remove('d-none');
    txt.textContent = 'Menyimpan...';
    btn.disabled = true;

    // Jangan disable _token & input file (agar tidak 419 dan file tetap terkirim)
    // Kalau mau lock field lain:
    // setTimeout(() => {
    //   form.querySelectorAll('input:not([type=hidden]):not([type=file]), select, textarea, button')
    //     .forEach(el => { if (el !== btn) el.disabled = true; });
    // }, 0);
  });
});
</script>
@endpush
