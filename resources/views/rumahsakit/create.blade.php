@extends('layouts.app')
@section('title','Tambah Rumah Sakit')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-success text-white">Tambah Rumah Sakit</div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ route('rumahsakit.store') }}">
      @csrf
      @include('rumahsakit.form', ['rs' => $rs])

      <div class="mt-3">
        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('rumahsakit.index') }}" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
