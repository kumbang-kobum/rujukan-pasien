@extends('layouts.app')
@section('title', 'Konsultasi Antar Dokter')

@section('content')
@php
    $badgeClasses = \App\Models\Konsultasi::statusBadgeClasses();
@endphp

<div class="card shadow-sm border-0">
    <div class="card-header bg-info text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span><i class="fas fa-comment-medical me-2"></i>Konsultasi Antar Dokter</span>
        <a href="{{ route('konsultasi.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Buat Konsultasi
        </a>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

        <form method="GET" action="{{ route('konsultasi.index') }}" class="border rounded p-3 bg-light mb-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label mb-1">Cari</label>
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="No rawat, no RM, nama pasien, judul">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Arah</label>
                    <select name="arah" class="form-select">
                        <option value="">Semua</option>
                        <option value="keluar" @selected(request('arah') === 'keluar')>Saya kirim</option>
                        <option value="masuk" @selected(request('arah') === 'masuk')>Untuk saya</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Per Halaman</label>
                    <select name="per_page" class="form-select">
                        @foreach([10, 25, 50] as $perPage)
                            <option value="{{ $perPage }}" @selected((int) request('per_page', 10) === $perPage)>{{ $perPage }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('konsultasi.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kasus</th>
                        <th>Pasien</th>
                        <th>Pengirim</th>
                        <th>Tujuan</th>
                        <th>Status</th>
                        <th>Update Terakhir</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($konsultasi as $index => $item)
                        <tr>
                            <td>{{ $konsultasi->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->judul }}</div>
                                <div class="small text-muted">{{ $item->kunjungan->no_rawat ?? '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $item->kunjungan->pasien->nama ?? '-' }}</div>
                                <div class="small text-muted">{{ $item->kunjungan->pasien->no_rkm_medis ?? '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $item->dokterPengirim->name ?? '-' }}</div>
                                <div class="small text-muted">{{ $item->rsAsal->nama ?? '-' }}</div>
                            </td>
                            <td>
                                <div>{{ $item->dokterTujuan->name ?? '-' }}</div>
                                <div class="small text-muted">{{ $item->rsTujuan->nama ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $badgeClasses[$item->status] ?? 'bg-secondary' }}">
                                    {{ $statuses[$item->status] ?? ucfirst($item->status) }}
                                </span>
                                @if($item->consent_status !== 'diberikan')
                                    <div class="small text-danger mt-1">Consent {{ $item->consent_status }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ optional($item->latestMessage)->created_at?->format('d/m/Y H:i') ?? $item->updated_at->format('d/m/Y H:i') }}</div>
                                <div class="small text-muted">
                                    {{ optional($item->latestMessage?->pengirim)->name ?? 'Belum ada balasan' }}
                                </div>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('konsultasi.show', $item) }}" class="btn btn-info btn-sm">Buka</a>
                                @if($item->status === \App\Models\Konsultasi::STATUS_DRAFT && (int) $item->dokter_pengirim_id === (int) auth()->id())
                                    <a href="{{ route('konsultasi.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada konsultasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $konsultasi->links() }}
        </div>
    </div>
</div>
@endsection
