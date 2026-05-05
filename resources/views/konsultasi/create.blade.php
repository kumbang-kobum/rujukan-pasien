@extends('layouts.app')
@section('title', 'Buat Konsultasi')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h4 class="mb-1">Buat Konsultasi Antar Dokter</h4>
        <div class="text-muted">Kirim ringkasan klinis yang relevan ke dokter tujuan di rumah sakit lain.</div>
    </div>
    <a href="{{ route('konsultasi.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Konsultasi belum bisa disimpan.</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@include('konsultasi._form')
@endsection
