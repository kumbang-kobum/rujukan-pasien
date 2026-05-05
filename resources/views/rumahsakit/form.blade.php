<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nama Rumah Sakit <span class="text-danger">*</span></label>
    <input name="nama"
           class="form-control @error('nama') is-invalid @enderror"
           value="{{ old('nama', $rs->nama ?? '') }}"
           maxlength="100" required>
    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Telepon</label>
    <input name="telepon"
           class="form-control @error('telepon') is-invalid @enderror"
           value="{{ old('telepon', $rs->telepon ?? '') }}"
           maxlength="50">
    @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-12">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" rows="3"
              class="form-control @error('alamat') is-invalid @enderror"
              maxlength="255">{{ old('alamat', $rs->alamat ?? '') }}</textarea>
    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>
