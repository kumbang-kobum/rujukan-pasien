@extends('layouts.app')
@section('title','Edit Kunjungan')
@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-warning">✏️ Edit Kunjungan</div>
    <div class="card-body">
        <form action="{{ route('kunjungan.update',$kunjungan->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Pasien</label>
                <select name="pasien_id" class="form-select" required>
                    @foreach($pasien as $p)
                        <option value="{{ $p->id }}" {{ $kunjungan->pasien_id==$p->id?'selected':'' }}>
                            {{ $p->nama }} ({{ $p->no_rkm_medis }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Pemeriksa (Dokter)</label>
                <input type="number" name="dokter_id" class="form-control" value="{{ $kunjungan->dokter_id }}" required>
            </div>

            <div class="mb-3">
                <label>Poli</label>
                <input type="text" name="poli" class="form-control" value="{{ $kunjungan->poli }}" required>
            </div>

            <div class="mb-3">
                <label>Tanggal Kunjungan</label>
                <input type="date" name="tanggal_kunjungan" class="form-control" value="{{ $kunjungan->tanggal_kunjungan }}" required>
            </div>

            <div class="mb-3">
                <label>Keluhan Utama</label>
                <textarea name="keluhan_utama" class="form-control">{{ $kunjungan->keluhan_utama }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
            <a href="{{ route('kunjungan.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection