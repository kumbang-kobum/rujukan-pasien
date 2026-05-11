@extends('layouts.app')
@section('title','Catatan SOAP — '.$soap->kunjungan->no_rawat)
@section('content')

{{-- Header info pasien --}}
<div class="card shadow-sm mb-3">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-notes-medical me-2"></i>Catatan SOAP — No. Rawat: <strong>{{ $soap->kunjungan->no_rawat }}</strong></span>
        <div class="d-flex gap-2">
            <a href="{{ route('soap.cetakSemua', $soap->id) }}" class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-print"></i> Cetak PDF
            </a>
            <a href="{{ route('soap.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body py-2">
        <div class="row row-cols-auto g-3 align-items-center">
            <div class="col"><strong>Pasien:</strong> {{ $soap->kunjungan->pasien->no_rkm_medis ?? '-' }} — {{ $soap->kunjungan->pasien->nama ?? '-' }}</div>
            <div class="col"><strong>Dokter:</strong> {{ $soap->kunjungan->dokter->name ?? '-' }}</div>
            <div class="col"><span class="badge bg-secondary">{{ $soapList->count() }} catatan SOAP</span></div>
        </div>
    </div>
</div>

{{-- Tabel kolom menyamping --}}
<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-top mb-0" style="min-width: {{ 140 + $soapList->count() * 220 }}px">
                <thead class="table-dark">
                    <tr>
                        <th class="text-nowrap" style="width:130px; min-width:130px; background:#343a40; position:sticky; left:0; z-index:2;">Keterangan</th>
                        @foreach($soapList as $s)
                            <th style="min-width:210px; vertical-align:top">
                                <div class="fw-bold">SOAP #{{ $loop->iteration }}</div>
                                <div class="fw-normal small">{{ $s->created_at->format('d/m/Y H:i') }}</div>
                                <div class="fw-normal small text-warning">{{ $s->user?->name ?? '-' }}</div>
                                @if($s->id === $soap->id)
                                    <span class="badge bg-warning text-dark mt-1">sedang dilihat</span>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{-- Tanda Vital --}}
                    <tr>
                        <th class="bg-light text-nowrap" style="position:sticky; left:0; z-index:1;">TD / MAP</th>
                        @foreach($soapList as $s)
                            <td>
                                @if($s->td_sys || $s->td_dia)
                                    {{ $s->td_sys ?? '?' }}/{{ $s->td_dia ?? '?' }} mmHg
                                    @if($s->map) <br><small class="text-muted">MAP: {{ $s->map }} mmHg</small>@endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    {{-- Subjektif --}}
                    <tr>
                        <th class="bg-light text-nowrap" style="position:sticky; left:0; z-index:1;">Subjektif (S)</th>
                        @foreach($soapList as $s)
                            <td style="white-space:pre-line; max-width:280px">{{ $s->subjektif ?? '—' }}</td>
                        @endforeach
                    </tr>
                    {{-- Objektif --}}
                    <tr>
                        <th class="bg-light text-nowrap" style="position:sticky; left:0; z-index:1;">Objektif (O)</th>
                        @foreach($soapList as $s)
                            <td style="max-width:280px">
                                <div style="white-space:pre-line">{{ $s->objektif ?? '—' }}</div>
                                @if($s->berkas->count())
                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        @foreach($s->berkas as $b)
                                            @php
                                                $ext = strtolower(pathinfo($b->path ?? '', PATHINFO_EXTENSION));
                                                $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                            @endphp
                                            @if($isImg)
                                                <a href="{{ route('berkas.file', $b) }}" target="_blank">
                                                    <img src="{{ route('berkas.file', $b) }}" style="max-height:80px; max-width:100px" class="rounded border">
                                                </a>
                                            @else
                                                <a href="{{ route('berkas.file', $b) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                    <i class="far fa-file"></i> {{ $b->nama_file }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    {{-- Assessment --}}
                    <tr>
                        <th class="bg-light text-nowrap" style="position:sticky; left:0; z-index:1;">Assessment (A)</th>
                        @foreach($soapList as $s)
                            <td style="white-space:pre-line; max-width:280px">{{ $s->assessment ?? '—' }}</td>
                        @endforeach
                    </tr>
                    {{-- Plan --}}
                    <tr>
                        <th class="bg-light text-nowrap" style="position:sticky; left:0; z-index:1;">Plan (P)</th>
                        @foreach($soapList as $s)
                            <td style="white-space:pre-line; max-width:280px">{{ $s->plan ?? '—' }}</td>
                        @endforeach
                    </tr>
                    {{-- Advice --}}
                    <tr>
                        <th class="bg-light text-nowrap" style="position:sticky; left:0; z-index:1;">Advice</th>
                        @foreach($soapList as $s)
                            <td style="white-space:pre-line; max-width:280px">{{ $s->advice ?? '—' }}</td>
                        @endforeach
                    </tr>
                    {{-- Aksi per SOAP --}}
                    <tr>
                        <th class="bg-light text-nowrap" style="position:sticky; left:0; z-index:1;">Aksi</th>
                        @foreach($soapList as $s)
                            <td class="text-nowrap">
                                <a href="{{ route('soap.edit', $s->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('soap.cetak', $s->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('soap.destroy', $s->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus SOAP #{{ $loop->iteration }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Berkas Medis --}}
@if($berkasKunjungan->isNotEmpty())
<div class="card shadow-sm">
    <div class="card-header">Berkas Medis Kunjungan</div>
    <div class="card-body p-0">
        <table class="table table-bordered table-sm mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama File</th>
                    <th>Uploader</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($berkasKunjungan as $i => $b)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ strtoupper($b->kategori ?? '-') }}</td>
                        <td><a href="{{ route('berkas.file', $b) }}" target="_blank">{{ $b->nama_file }}</a></td>
                        <td>{{ $b->uploader->name ?? '-' }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('berkas.edit', ['berka' => $b->id, 'redirect' => route('soap.show', $soap->id)]) }}"
                               class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('berkas.destroy', $b->id) }}?redirect={{ urlencode(route('soap.show',$soap->id)) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
