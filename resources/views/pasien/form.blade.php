
<div class="col-md-6 mb-3">
    <label class="form-label">No. Rekam Medis</label>
    <input type="text" name="no_rkm_medis" class="form-control @error('no_rkm_medis') is-invalid @enderror"
           value="{{ old('no_rkm_medis', $pasien->no_rkm_medis ?? '') }}">
    @error('no_rkm_medis') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">NIK</label>
        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
               value="{{ old('nik', $pasien->nik ?? '') }}">
        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
               value="{{ old('nama', $pasien->nama ?? '') }}">
        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
               value="{{ old('tanggal_lahir', $pasien->tanggal_lahir ?? '') }}">
        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
            <option value="L" {{ old('jenis_kelamin',$pasien->jenis_kelamin ?? '')=='L'?'selected':'' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin',$pasien->jenis_kelamin ?? '')=='P'?'selected':'' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Telepon</label>
        <input type="text" name="telepon" class="form-control"
               value="{{ old('telepon', $pasien->telepon ?? '') }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
              rows="2">{{ old('alamat', $pasien->alamat ?? '') }}</textarea>
    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>