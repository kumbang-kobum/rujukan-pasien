@extends('layouts.app')
@section('title','Edit Pasien')
@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">✏️ Edit Pasien</div>
    <div class="card-body">
        <form action="{{ route('pasien.update',$pasien->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label>No RM</label>
                <input type="text" name="no_rkm_medis" class="form-control" value="{{ $pasien->no_rkm_medis }}" required>
            </div>
            <div class="mb-3">
                <label>NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ $pasien->nik }}">
            </div>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $pasien->nama }}" required>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir',$pasien->tempat_lahir) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir',$pasien->tanggal_lahir) }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="L" {{ $pasien->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ $pasien->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control">{{ $pasien->alamat }}</textarea>
            </div>
            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ $pasien->telepon }}">
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="{{ route('pasien.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection