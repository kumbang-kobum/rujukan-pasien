@extends('layouts.app')
@section('title','Detail Rumah Sakit')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-info text-white">Detail Rumah Sakit</div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">Nama</dt>
      <dd class="col-sm-9">{{ $rumah_sakit->nama }}</dd>

      <dt class="col-sm-3">Telepon</dt>
      <dd class="col-sm-9">{{ $rumah_sakit->telepon ?? '-' }}</dd>

      <dt class="col-sm-3">Alamat</dt>
      <dd class="col-sm-9">{{ $rumah_sakit->alamat ?? '-' }}</dd>
    </dl>

    <a href="{{ route('rumahsakit.edit', $rumahsakit->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('rumahsakit.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>
@endsection
