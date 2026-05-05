@extends('layouts.app')
@section('title','Detail Rujukan')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-info text-white">Detail Rujukan #{{ $rujukan->id }}</div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th>No Rawat</th>
        <td>{{ $rujukan->kunjungan->no_rawat ?? $rujukan->kunjungan->id }}</td>
      </tr>
      <tr>
        <th>Pasien</th>
        <td>{{ $rujukan->kunjungan->pasien->no_rkm_medis ?? '-' }} - {{ $rujukan->kunjungan->pasien->nama ?? '-' }}</td>
      </tr>
      <tr>
        <th>Rumah Sakit Asal</th>
        <td>{{ $rujukan->rsAsal->nama ?? '-' }}</td>
      </tr>
      <tr>
        <th>Rumah Sakit Tujuan</th>
        <td>{{ $rujukan->rsTujuan->nama ?? '-' }}</td>
      </tr>
      <tr>
        <th>Dokter Tujuan</th>
        <td>{{ $rujukan->dokterTujuan->name ?? '-' }}</td>
      </tr>
      <tr>
        <th>Alasan</th>
        <td>{{ $rujukan->alasan }}</td>
      </tr>
      <tr>
        <th>Alasan Rujukan</th>
        <td>{{ $rujukan->alasan_rujukan }}</td>
      </tr>
      <tr>
        <th>Catatan</th>
        <td>{{ $rujukan->catatan }}</td>
      </tr>
      <tr>
        <th>Status</th>
        <td>
          @if($rujukan->status == 'menunggu')
            <span class="badge bg-secondary">Menunggu</span>
          @elseif($rujukan->status == 'diterima')
            <span class="badge bg-success">Diterima</span>
          @else
            <span class="badge bg-danger">Ditolak</span>
          @endif
        </td>
      </tr>
      <tr>
        <th>Catatan Penerima</th>
        <td>{{ $rujukan->catatan_penerima ?? '-' }}</td>
      </tr>
      <tr>
        <th>Dibuat</th>
        <td>{{ $rujukan->created_at->format('d/m/Y H:i') }}</td>
      </tr>
      <tr>
        <th>Asal Konsultasi</th>
        <td>
          @if($rujukan->originKonsultasi)
            <a href="{{ route('konsultasi.show', $rujukan->originKonsultasi) }}">{{ $rujukan->originKonsultasi->no_konsultasi }}</a>
          @else
            -
          @endif
        </td>
      </tr>
      <tr>
        <th>Diperbarui</th>
        <td>{{ $rujukan->updated_at->format('d/m/Y H:i') }}</td>
      </tr>
    </table>

    <a href="{{ route('rujukan.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('rujukan.edit',$rujukan->id) }}" class="btn btn-warning">Edit</a>
  </div>
</div>
@endsection
