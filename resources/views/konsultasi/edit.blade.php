@extends('layouts.app')
@section('title', 'Edit Konsultasi')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h4 class="mb-1">Edit Draft Konsultasi</h4>
        <div class="text-muted">Draft masih bisa diubah sebelum dikirim ke dokter tujuan.</div>
    </div>
    <a href="{{ route('konsultasi.show', $konsultasi) }}" class="btn btn-outline-secondary">Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Perubahan belum bisa disimpan.</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@include('konsultasi._form')
@endsection
