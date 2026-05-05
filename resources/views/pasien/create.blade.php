@extends('layouts.app')
@section('title','Tambah Pasien')
@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">+ Tambah Pasien</div>
    <div class="card-body">
        <form action="{{ route('pasien.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>No RM</label>
                <input type="text" name="no_rkm_medis" class="form-control"
                    value="{{ $no_rkm_medis }}" readonly>
            </div>
            <div class="mb-3">
                <label>NIK</label>
                <input type="text" name="nik" class="form-control">
            </div>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                </div>
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="" disabled selected>Pilih...</option>
                    <option value="L" {{ old('jenis_kelamin')=='L' ? 'selected':'' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin')=='P' ? 'selected':'' }}>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('pasien.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
