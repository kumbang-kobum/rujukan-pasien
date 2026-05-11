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
        <th>Diperbarui</th>
        <td>{{ $rujukan->updated_at->format('d/m/Y H:i') }}</td>
      </tr>
    </table>

    <a href="{{ route('rujukan.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('rujukan.edit',$rujukan->id) }}" class="btn btn-warning">Edit</a>
  </div>
</div>

{{-- SOAP dari RS Asal --}}
@php($soapList = $rujukan->kunjungan?->soap ?? collect())
@if($soapList->isNotEmpty())
<div class="card shadow-sm mt-4">
  <div class="card-header bg-secondary text-white">
    Catatan SOAP dari {{ $rujukan->rsAsal?->nama ?? 'RS Asal' }}
  </div>
  <div class="card-body">
    @foreach($soapList as $soap)
      <div class="border rounded p-3 mb-3">
        <div class="d-flex justify-content-between mb-2">
          <strong>SOAP #{{ $soap->id }}</strong>
          <small class="text-muted">{{ $soap->created_at->format('d/m/Y H:i') }} &mdash; {{ $soap->user?->name ?? '-' }}</small>
        </div>

        @if($soap->td_sys || $soap->td_dia || $soap->map)
          <p class="mb-1"><strong>Tanda Vital:</strong>
            TD {{ $soap->td_sys ?? '?' }}/{{ $soap->td_dia ?? '?' }} mmHg,
            MAP {{ $soap->map ?? '?' }} mmHg
          </p>
        @endif

        @if($soap->subjektif)
          <p class="mb-1"><strong>S:</strong> {!! nl2br(e($soap->subjektif)) !!}</p>
        @endif
        @if($soap->objektif)
          <p class="mb-1"><strong>O:</strong> {!! nl2br(e($soap->objektif)) !!}</p>
        @endif
        @if($soap->assessment)
          <p class="mb-1"><strong>A:</strong> {!! nl2br(e($soap->assessment)) !!}</p>
        @endif
        @if($soap->plan)
          <p class="mb-1"><strong>P:</strong> {!! nl2br(e($soap->plan)) !!}</p>
        @endif
        @if($soap->advice)
          <p class="mb-0"><strong>Advice:</strong> {!! nl2br(e($soap->advice)) !!}</p>
        @endif
      </div>
    @endforeach
  </div>
</div>
@endif
@endsection
