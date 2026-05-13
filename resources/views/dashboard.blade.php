@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $currentUser = Auth::user();
    $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $seriesPasien = $seriesPasien ?? array_fill(0, 12, 0);
    $seriesRujukan = $seriesRujukan ?? array_fill(0, 12, 0);
    $statusCounts = $statusCounts ?? ['menunggu' => 0, 'diterima' => 0, 'ditolak' => 0];
    $maxSeriesValue = max(array_merge($seriesPasien, $seriesRujukan, [1]));
    $statusTotal = max(array_sum($statusCounts), 1);

    $metrics = [
        [
            'label' => $currentUser->isSuperAdmin() ? 'Total Pasien' : 'Pasien RS Saya',
            'value' => $pasienCount,
            'icon' => 'fa-user-injured',
            'tone' => 'blue',
            'note' => $currentUser->isSuperAdmin() ? 'Semua pasien terdaftar' : 'Dari kunjungan yang terlihat',
        ],
        [
            'label' => $kunjunganLabel,
            'value' => $kunjunganCount,
            'icon' => 'fa-stethoscope',
            'tone' => 'emerald',
            'note' => number_format($kunjunganHariIniCount, 0, ',', '.').' kunjungan hari ini',
        ],
        [
            'label' => $rujukanKirimLabel,
            'value' => $rujukanKirimCount,
            'icon' => 'fa-paper-plane',
            'tone' => 'amber',
            'note' => $currentUser->isSuperAdmin() ? 'Rujukan lintas platform' : 'Dikirim dari RS ini',
        ],
        [
            'label' => $rujukanTerimaLabel,
            'value' => $rujukanTerimaCount,
            'icon' => $currentUser->isSuperAdmin() ? 'fa-hospital' : 'fa-inbox',
            'tone' => 'rose',
            'note' => $currentUser->isSuperAdmin() ? 'Fasilitas terdaftar' : 'Masuk ke RS ini',
        ],
    ];

    $statusStyles = [
        'menunggu' => ['label' => 'Menunggu', 'class' => 'warning', 'icon' => 'fa-clock'],
        'diterima' => ['label' => 'Diterima', 'class' => 'success', 'icon' => 'fa-check-circle'],
        'ditolak' => ['label' => 'Ditolak', 'class' => 'danger', 'icon' => 'fa-times-circle'],
    ];

    $quickActions = [];
    if ($currentUser->canAccessClinical()) {
        $quickActions[] = ['label' => 'Pasien', 'href' => route('pasien.index'), 'icon' => 'fa-user-injured', 'tone' => 'blue'];
        $quickActions[] = ['label' => 'Kunjungan', 'href' => route('kunjungan.index'), 'icon' => 'fa-clipboard-list', 'tone' => 'emerald'];
        $quickActions[] = ['label' => 'Rujukan', 'href' => route('rujukan.index'), 'icon' => 'fa-exchange-alt', 'tone' => 'amber'];
    }
    if ($currentUser->isAdminRs() || $currentUser->isDokter()) {
        $quickActions[] = ['label' => 'Konsultasi', 'href' => route('konsultasi.index'), 'icon' => 'fa-comment-medical', 'tone' => 'rose'];
    }
    if ($currentUser->canManageHospitals()) {
        $quickActions[] = ['label' => 'Rumah Sakit', 'href' => route('rumahsakit.index'), 'icon' => 'fa-hospital', 'tone' => 'blue'];
    }
    if ($currentUser->canManageUsers()) {
        $quickActions[] = ['label' => 'Pengguna', 'href' => route('users.index'), 'icon' => 'fa-users-cog', 'tone' => 'slate'];
    }
@endphp

@push('styles')
<style>
    .dashboard-page {
        display: grid;
        gap: 1.25rem;
    }

    .dashboard-panel,
    .dashboard-metric,
    .dashboard-action {
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 8px;
        background: rgba(255, 255, 255, .86);
        box-shadow: 0 10px 26px rgba(15, 23, 42, .06);
    }

    .dashboard-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem;
    }

    .dashboard-title {
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: 0;
        color: #0f172a;
        margin: 0;
    }

    .dashboard-scope {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        max-width: 100%;
        border: 1px solid rgba(37, 99, 235, .18);
        border-radius: 999px;
        padding: .45rem .75rem;
        background: rgba(239, 246, 255, .86);
        color: #1d4ed8;
        font-size: .88rem;
        font-weight: 600;
    }

    .dashboard-metrics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .dashboard-metric {
        position: relative;
        overflow: hidden;
        padding: 1rem;
        min-height: 134px;
    }

    .dashboard-metric::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 4px;
        background: var(--metric-color);
    }

    .metric-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: .75rem;
    }

    .metric-label {
        color: #475569;
        font-size: .88rem;
        font-weight: 700;
    }

    .metric-value {
        margin-top: .55rem;
        color: #0f172a;
        font-size: 2.1rem;
        font-weight: 800;
        line-height: 1;
    }

    .metric-note {
        margin-top: .65rem;
        color: #64748b;
        font-size: .82rem;
    }

    .metric-icon,
    .action-icon {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: var(--metric-color);
        background: var(--metric-bg);
        flex: 0 0 auto;
    }

    .tone-blue { --metric-color: #2563eb; --metric-bg: #dbeafe; }
    .tone-emerald { --metric-color: #059669; --metric-bg: #d1fae5; }
    .tone-amber { --metric-color: #d97706; --metric-bg: #fef3c7; }
    .tone-rose { --metric-color: #e11d48; --metric-bg: #ffe4e6; }
    .tone-slate { --metric-color: #475569; --metric-bg: #e2e8f0; }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(320px, .65fr);
        gap: 1rem;
        align-items: stretch;
    }

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1rem 0;
    }

    .panel-title {
        margin: 0;
        color: #0f172a;
        font-size: 1rem;
        font-weight: 800;
    }

    .panel-body {
        padding: 1rem;
    }

    .status-row {
        display: grid;
        gap: .85rem;
    }

    .status-line {
        display: grid;
        gap: .45rem;
    }

    .status-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        font-size: .9rem;
        color: #334155;
    }

    .progress {
        height: .6rem;
        border-radius: 999px;
        background: #e2e8f0;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .75rem;
    }

    .dashboard-action {
        display: flex;
        align-items: center;
        gap: .75rem;
        min-height: 72px;
        padding: .8rem;
        color: #0f172a;
        text-decoration: none;
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    }

    .dashboard-action:hover {
        color: #0f172a;
        transform: translateY(-1px);
        border-color: rgba(37, 99, 235, .28);
        box-shadow: 0 14px 28px rgba(15, 23, 42, .09);
    }

    .action-label {
        font-weight: 800;
        line-height: 1.2;
    }

    .month-chart {
        display: grid;
        grid-template-columns: repeat(12, minmax(48px, 1fr));
        gap: .65rem;
        overflow-x: auto;
        padding-bottom: .2rem;
    }

    .month-bars {
        height: 150px;
        display: flex;
        align-items: end;
        justify-content: center;
        gap: 4px;
        border-bottom: 1px solid #cbd5e1;
        padding: 0 .25rem;
    }

    .month-bar {
        width: 42%;
        border-radius: 5px 5px 0 0;
        min-height: 0;
    }

    .month-bar.pasien { background: #2563eb; }
    .month-bar.rujukan { background: #059669; }

    .month-label {
        margin-top: .5rem;
        color: #64748b;
        font-size: .78rem;
        text-align: center;
    }

    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        color: #475569;
        font-size: .84rem;
    }

    .legend-dot {
        width: .65rem;
        height: .65rem;
        display: inline-block;
        border-radius: 999px;
        margin-right: .35rem;
    }

    .table-dashboard {
        margin: 0;
    }

    .table-dashboard th {
        color: #475569;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .02em;
        border-top: 0;
    }

    .empty-state {
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 1.5rem;
        color: #64748b;
        text-align: center;
        background: rgba(248, 250, 252, .75);
    }

    @media (max-width: 1199px) {
        .dashboard-metrics {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767px) {
        .dashboard-hero {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-title {
            font-size: 1.4rem;
        }

        .dashboard-metrics,
        .quick-actions {
            grid-template-columns: 1fr;
        }

        .metric-value {
            font-size: 1.85rem;
        }
    }
</style>
@endpush

<div class="dashboard-page">
    <section class="dashboard-panel dashboard-hero">
        <div>
            <h1 class="dashboard-title">
                <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
            </h1>
            <div class="text-muted mt-1">Ringkasan aktivitas rujukan pasien tahun {{ $tahun }}</div>
        </div>
        <div class="dashboard-scope">
            <i class="fas fa-shield-alt"></i>
            <span>{{ $scopeLabel }}</span>
        </div>
    </section>

    <section class="dashboard-metrics">
        @foreach($metrics as $metric)
            <article class="dashboard-metric tone-{{ $metric['tone'] }}">
                <div class="metric-top">
                    <div>
                        <div class="metric-label">{{ $metric['label'] }}</div>
                        <div class="metric-value">{{ number_format($metric['value'], 0, ',', '.') }}</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas {{ $metric['icon'] }}"></i>
                    </div>
                </div>
                <div class="metric-note">{{ $metric['note'] }}</div>
            </article>
        @endforeach
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-panel">
            <div class="panel-header">
                <h2 class="panel-title"><i class="fas fa-chart-column me-2 text-primary"></i>Statistik Bulanan</h2>
                <div class="chart-legend">
                    <span><span class="legend-dot" style="background:#2563eb"></span>Pasien</span>
                    <span><span class="legend-dot" style="background:#059669"></span>Rujukan</span>
                </div>
            </div>
            <div class="panel-body">
                <div class="month-chart" aria-label="Statistik bulanan pasien dan rujukan">
                    @foreach($months as $index => $month)
                        @php
                            $pasienValue = (int) ($seriesPasien[$index] ?? 0);
                            $rujukanValue = (int) ($seriesRujukan[$index] ?? 0);
                            $pasienHeight = $pasienValue > 0 ? max(6, round(($pasienValue / $maxSeriesValue) * 100)) : 0;
                            $rujukanHeight = $rujukanValue > 0 ? max(6, round(($rujukanValue / $maxSeriesValue) * 100)) : 0;
                        @endphp
                        <div>
                            <div class="month-bars" title="{{ $month }}: {{ $pasienValue }} pasien, {{ $rujukanValue }} rujukan">
                                <div class="month-bar pasien" style="height: {{ $pasienHeight }}%"></div>
                                <div class="month-bar rujukan" style="height: {{ $rujukanHeight }}%"></div>
                            </div>
                            <div class="month-label">{{ $month }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="dashboard-panel">
            <div class="panel-header">
                <h2 class="panel-title"><i class="fas fa-list-check me-2 text-success"></i>Status Rujukan</h2>
            </div>
            <div class="panel-body">
                <div class="status-row">
                    @foreach($statusStyles as $status => $style)
                        @php
                            $count = (int) ($statusCounts[$status] ?? 0);
                            $percent = round(($count / $statusTotal) * 100);
                        @endphp
                        <div class="status-line">
                            <div class="status-meta">
                                <span><i class="fas {{ $style['icon'] }} me-1 text-{{ $style['class'] }}"></i>{{ $style['label'] }}</span>
                                <strong>{{ number_format($count, 0, ',', '.') }}</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-{{ $style['class'] }}" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </section>

    @if(count($quickActions) > 0)
        <section class="dashboard-panel">
            <div class="panel-header">
                <h2 class="panel-title"><i class="fas fa-folder-open me-2 text-warning"></i>Menu Utama</h2>
            </div>
            <div class="panel-body">
                <div class="quick-actions">
                    @foreach($quickActions as $action)
                        <a href="{{ $action['href'] }}" class="dashboard-action tone-{{ $action['tone'] }}">
                            <span class="action-icon"><i class="fas {{ $action['icon'] }}"></i></span>
                            <span class="action-label">{{ $action['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="dashboard-panel">
        <div class="panel-header">
            <h2 class="panel-title"><i class="fas fa-clock-rotate-left me-2 text-primary"></i>Aktivitas Rujukan Terbaru</h2>
            @if($currentUser->canAccessClinical())
                <a href="{{ route('rujukan.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-right me-1"></i> Lihat Semua
                </a>
            @endif
        </div>
        <div class="panel-body">
            @if(($latestRujukan ?? collect())->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-dashboard">
                        <thead>
                            <tr>
                                <th>No. Rawat</th>
                                <th>Pasien</th>
                                <th>Asal</th>
                                <th>Tujuan</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                @if($currentUser->canAccessClinical())
                                    <th class="text-end">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestRujukan as $rujukan)
                                @php
                                    $badgeClass = [
                                        'menunggu' => 'text-bg-warning',
                                        'diterima' => 'text-bg-success',
                                        'ditolak' => 'text-bg-danger',
                                    ][$rujukan->status] ?? 'text-bg-secondary';
                                @endphp
                                <tr>
                                    <td class="fw-semibold text-nowrap">{{ $rujukan->kunjungan->no_rawat ?? '-' }}</td>
                                    <td>{{ $rujukan->kunjungan->pasien->nama ?? '-' }}</td>
                                    <td>{{ $rujukan->rsAsal->nama ?? '-' }}</td>
                                    <td>{{ $rujukan->rsTujuan->nama ?? '-' }}</td>
                                    <td><span class="badge {{ $badgeClass }}">{{ ucfirst($rujukan->status) }}</span></td>
                                    <td class="text-nowrap">{{ $rujukan->created_at ? $rujukan->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    @if($currentUser->canAccessClinical())
                                        <td class="text-end">
                                            <a href="{{ route('rujukan.show', $rujukan) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    Belum ada aktivitas rujukan yang bisa ditampilkan.
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
