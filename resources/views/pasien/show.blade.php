@extends('layouts.app')
@section('title','Detail Pasien')
@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-info text-white">👤 Detail Pasien</div>
    <div class="card-body">
        <ul class="list-group">
            <li class="list-group-item"><strong>No RM:</strong> {{ $pasien->no_rkm_medis }}</li>
            <li class="list-group-item"><strong>NIK:</strong> {{ $pasien->nik }}</li>
            <li class="list-group-item"><strong>Nama:</strong> {{ $pasien->nama }}</li>
            <li class="list-group-item"><strong>Jenis Kelamin:</strong> {{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</li>
            <li class="list-group-item"><strong>Alamat:</strong> {{ $pasien->alamat }}</li>
            <li class="list-group-item"><strong>Telepon:</strong> {{ $pasien->telepon }}</li>
        </ul>
        <div class="mt-3">
            <a href="{{ route('pasien.edit',$pasien->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('pasien.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection