@extends('layouts.app')
@section('title','Edit Berkas')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-warning text-white">Edit Berkas Medis</div>
  <div class="card-body">
    <form action="{{ route('berkas.update',$berka->id) }}" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="mb-3">
        <label>Jenis Berkas</label>
        <input type="text" name="jenis" class="form-control" value="{{ $berka->jenis }}">
      </div>
      <div class="mb-3">
        <label>File Baru (opsional)</label>
        <input type="file" name="file" class="form-control">
        <small class="text-muted">File lama: {{ $berka->nama_file }}</small>
      </div>
      <button class="btn btn-primary">Update</button>
      <a href="{{ route('kunjungan.show',$berka->kunjungan_id) }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection