@extends('layouts.app')
@section('title','Tambah Kunjungan')
@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-success text-white">+ Tambah Kunjungan</div>
  <div class="card-body">
    <form method="POST" action="{{ route('kunjungan.store') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Pasien</label>
        <select name="pasien_id" class="form-select select2" required>
          <option value="" disabled selected>Pilih...</option>
          @foreach($pasien as $p)
            <option value="{{ $p->id }}">{{ $p->no_rkm_medis }} - {{ $p->nama }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Dokter</label>
        <select name="dokter_id" class="form-select" required>
          <option value="" disabled selected>Pilih...</option>
          @foreach($dokter as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Poli</label>
        <input type="text" name="poli" class="form-control" required>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Tanggal Kunjungan</label>
          <input type="date" name="tanggal_kunjungan" class="form-control" value="{{ now()->toDateString() }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Waktu Masuk</label>
          <input type="time" name="waktu_masuk" class="form-control" value="{{ now()->format('H:i') }}" required>
        </div>
      </div>

      <div class="mt-3 mb-3">
        <label class="form-label">Keluhan Utama</label>
        <textarea name="keluhan_utama" class="form-control" rows="2"></textarea>
      </div>

      <button class="btn btn-success">Simpan</button>
      <a href="{{ route('kunjungan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
      $('.select2').select2({
          placeholder: "Cari pasien berdasarkan No. RM atau Nama",
          allowClear: true
      });
  });
</script>
@endpush