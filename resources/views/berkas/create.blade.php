@extends('layouts.app')
@section('title','Upload Berkas')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-success text-white">Upload Berkas Medis</div>
  <div class="card-body">
    <form action="{{ route('berkas.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="kunjungan_id" value="{{ $kunjungan->id }}">
      <div class="mb-3">
        <label>Jenis Berkas</label>
        <input type="text" name="jenis" class="form-control" placeholder="Lab / Radiologi / Lainnya">
      </div>
      <div class="mb-3">
        <label>Pilih File</label>
        <input type="file" name="file" class="form-control" required>
      </div>
      <button class="btn btn-primary">Upload</button>
      <a href="{{ route('kunjungan.show',$kunjungan->id) }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection