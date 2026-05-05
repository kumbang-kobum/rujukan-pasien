@extends('layouts.app')
@section('title','Tambah Konsultasi')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">
    <i class="fas fa-comments-medical me-2"></i> Tambah Konsultasi Antar Dokter
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('konsultasi.store') }}">
      @csrf
      @include('konsultasi.form')

      <div class="mt-4 d-flex gap-2 flex-wrap">
        <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">
          <i class="fas fa-save me-1"></i> Simpan Draft
        </button>
        <button type="submit" name="action" value="submit" class="btn btn-primary">
          <i class="fas fa-paper-plane me-1"></i> Simpan & Kirim
        </button>
        <a href="{{ route('konsultasi.index') }}" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
