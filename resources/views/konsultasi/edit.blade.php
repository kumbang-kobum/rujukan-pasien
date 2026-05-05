@extends('layouts.app')
@section('title','Edit Konsultasi')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-warning text-dark">
    <i class="fas fa-edit me-2"></i> Edit Konsultasi {{ $konsultasi->no_konsultasi }}
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('konsultasi.update', $konsultasi) }}">
      @csrf
      @method('PUT')
      @include('konsultasi.form')

      <div class="mt-4 d-flex gap-2 flex-wrap">
        <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">
          <i class="fas fa-save me-1"></i> Simpan Perubahan
        </button>
        <button type="submit" name="action" value="submit" class="btn btn-warning">
          <i class="fas fa-paper-plane me-1"></i> Simpan & Kirim
        </button>
        <a href="{{ route('konsultasi.show', $konsultasi) }}" class="btn btn-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>
@endsection
