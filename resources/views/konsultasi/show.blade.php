@extends('layouts.app')
@section('title', 'Detail Konsultasi')

@section('content')
@php
    $statusLabels = \App\Models\Konsultasi::statusLabels();
    $statusClasses = \App\Models\Konsultasi::statusBadgeClasses();
    $isSender = (int) $konsultasi->dokter_pengirim_id === (int) auth()->id();
    $isTarget = (int) $konsultasi->dokter_tujuan_id === (int) auth()->id();
    $canReply = $konsultasi->canReply() && ($isSender || $isTarget);
@endphp

<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
    <div>
        <h4 class="mb-1">{{ $konsultasi->judul }}</h4>
        <div class="text-muted">
            No Rawat {{ $konsultasi->kunjungan->no_rawat ?? '-' }} · Pasien {{ $konsultasi->kunjungan->pasien->nama ?? '-' }}
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('konsultasi.index') }}" class="btn btn-outline-secondary">Kembali</a>
        @if($isSender && $konsultasi->status === \App\Models\Konsultasi::STATUS_DRAFT)
            <a href="{{ route('konsultasi.edit', $konsultasi) }}" class="btn btn-warning">Edit Draft</a>
            <form method="POST" action="{{ route('konsultasi.destroy', $konsultasi) }}" onsubmit="return confirm('Hapus draft konsultasi ini?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Hapus Draft</button>
            </form>
        @endif
    </div>
</div>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif

<div class="row g-3">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>Ringkasan Konsultasi</span>
                <span class="badge {{ $statusClasses[$konsultasi->status] ?? 'bg-secondary' }}">
                    {{ $statusLabels[$konsultasi->status] ?? ucfirst($konsultasi->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small text-muted">Dokter Pengirim</div>
                        <div>{{ $konsultasi->dokterPengirim->name ?? '-' }}</div>
                        <div class="small text-muted">{{ $konsultasi->rsAsal->nama ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Dokter Tujuan</div>
                        <div>{{ $konsultasi->dokterTujuan->name ?? '-' }}</div>
                        <div class="small text-muted">{{ $konsultasi->rsTujuan->nama ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Status Consent</div>
                        <div class="fw-semibold text-capitalize">{{ $konsultasi->consent_status }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Dikirim</div>
                        <div>{{ optional($konsultasi->submitted_at)->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <div class="small text-muted">Alasan Konsultasi</div>
                    <div class="multiline">{{ $konsultasi->alasan_konsultasi }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Pertanyaan Klinis</div>
                    <div class="multiline">{{ $konsultasi->pertanyaan_konsultasi ?: '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Ringkasan Klinis</div>
                    <div class="multiline">{{ $konsultasi->ringkasan_klinis ?: '-' }}</div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small text-muted">Diagnosis Kerja</div>
                        <div class="multiline">{{ $konsultasi->diagnosis_kerja ?: '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Terapi Berjalan</div>
                        <div class="multiline">{{ $konsultasi->terapi_berjalan ?: '-' }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="small text-muted">Hasil Penunjang</div>
                    <div class="multiline">{{ $konsultasi->hasil_penunjang ?: '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-light fw-semibold">Percakapan Konsultasi</div>
            <div class="card-body">
                @forelse($konsultasi->pesan as $message)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <div>
                                <div class="fw-semibold">{{ $message->pengirim->name ?? '-' }}</div>
                                <div class="small text-muted">{{ \App\Models\KonsultasiPesan::typeLabels()[$message->tipe] ?? ucfirst($message->tipe) }}</div>
                            </div>
                            <div class="small text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="multiline">{{ $message->pesan }}</div>
                    </div>
                @empty
                    <div class="text-muted">Belum ada balasan. Dokter tujuan bisa mulai merespons setelah membuka konsultasi ini.</div>
                @endforelse

                @if($canReply)
                    <hr>
                    <h6 class="mb-3">Balas Konsultasi</h6>
                    <form method="POST" action="{{ route('konsultasi.reply', $konsultasi) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Jenis Balasan</label>
                                <select name="tipe" class="form-select" required>
                                    @foreach($replyTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Pesan</label>
                                <textarea name="pesan" rows="4" class="form-control" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-semibold">Audit Trail</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Aksi</th>
                                <th>Pengguna</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($konsultasi->auditLogs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $log->aksi)) }}</td>
                                    <td>{{ $log->user->name ?? '-' }}</td>
                                    <td class="small text-muted">
                                        @if($log->payload)
                                            {{ collect($log->payload)->map(fn ($value, $key) => $key.': '.$value)->implode(' | ') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada log.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-light fw-semibold">Aksi</div>
            <div class="card-body d-grid gap-2">
                @if($isTarget && in_array($konsultasi->status, [\App\Models\Konsultasi::STATUS_TERKIRIM, \App\Models\Konsultasi::STATUS_DIBACA], true))
                    <form method="POST" action="{{ route('konsultasi.accept', $konsultasi) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">ACC Konsultasi</button>
                    </form>
                @endif

                @if(($isSender || $isTarget) && !in_array($konsultasi->status, [\App\Models\Konsultasi::STATUS_DRAFT, \App\Models\Konsultasi::STATUS_DITUTUP, \App\Models\Konsultasi::STATUS_DIRUJUK], true))
                    <form method="POST" action="{{ route('konsultasi.close', $konsultasi) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-secondary w-100">Tutup Konsultasi</button>
                    </form>
                @endif

                @if($isSender && $konsultasi->consent_status === \App\Models\Konsultasi::CONSENT_DIBERIKAN && !$konsultasi->rujukan_id && $konsultasi->status !== \App\Models\Konsultasi::STATUS_DRAFT)
                    <form method="POST" action="{{ route('konsultasi.escalate', $konsultasi) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">Lanjutkan Menjadi Rujukan</button>
                    </form>
                @endif

                @if($konsultasi->rujukan_id)
                    <a href="{{ route('rujukan.show', $konsultasi->rujukan_id) }}" class="btn btn-outline-primary w-100">
                        Buka Rujukan Resmi
                    </a>
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-light fw-semibold">Persetujuan Pasien</div>
            <div class="card-body small">
                <div><strong>Status:</strong> {{ ucfirst($konsultasi->consent_status) }}</div>
                <div><strong>Pemberi:</strong> {{ $konsultasi->consent_nama_pemberi ?? '-' }}</div>
                <div><strong>Hubungan:</strong> {{ $konsultasi->consent_hubungan ?? '-' }}</div>
                <div><strong>Metode:</strong> {{ $konsultasi->consent_metode ?? '-' }}</div>
                <div><strong>Waktu:</strong> {{ optional($konsultasi->consent_diberikan_pada)->format('d/m/Y H:i') ?? '-' }}</div>
                <div class="mt-2"><strong>Catatan:</strong></div>
                <div class="multiline">{{ $konsultasi->consent_catatan ?: '-' }}</div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-light fw-semibold">Kunjungan & SOAP</div>
            <div class="card-body small">
                <div><strong>Dokter asal:</strong> {{ $konsultasi->kunjungan->dokter->name ?? '-' }}</div>
                <div><strong>Keluhan utama:</strong> {{ $konsultasi->kunjungan->keluhan_utama ?? '-' }}</div>
                @if($latestSoap)
                    <hr>
                    <div class="fw-semibold">SOAP Terakhir</div>
                    <div class="mt-2"><strong>Subjektif:</strong></div>
                    <div class="multiline">{{ $latestSoap->subjektif ?? '-' }}</div>
                    <div class="mt-2"><strong>Objektif:</strong></div>
                    <div class="multiline">{{ $latestSoap->objektif ?? '-' }}</div>
                    <div class="mt-2"><strong>Assessment:</strong></div>
                    <div class="multiline">{{ $latestSoap->assessment ?? '-' }}</div>
                    <div class="mt-2"><strong>Plan:</strong></div>
                    <div class="multiline">{{ $latestSoap->plan ?? '-' }}</div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light fw-semibold">Berkas Medis Terkait</div>
            <div class="card-body small">
                @forelse($konsultasi->kunjungan->berkasMedis as $berkas)
                    <div class="border rounded p-2 mb-2">
                        <div class="fw-semibold">{{ $berkas->nama_file }}</div>
                        <div class="text-muted">{{ strtoupper($berkas->kategori ?? $berkas->mime ?? 'berkas') }}</div>
                        <a href="{{ route('berkas.file', $berkas) }}" target="_blank" class="small">Buka berkas</a>
                    </div>
                @empty
                    <div class="text-muted">Belum ada berkas medis yang terhubung ke kunjungan ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
