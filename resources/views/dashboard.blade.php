@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    // Jaga-jaga: pastikan variabel ada
    $pasienPerBulan   = $pasienPerBulan   ?? [];
    $rujukanPerBulan  = $rujukanPerBulan  ?? [];

    // Buat 12 elemen (bulan 1..12) lalu reindex ke [0..11]
    $seriesPasien     = array_values(array_replace(array_fill(1, 12, 0), $pasienPerBulan));
    $seriesRujukan    = array_values(array_replace(array_fill(1, 12, 0), $rujukanPerBulan));

    $tahun = date('Y');
    $currentUser = Auth::user();
    $rujukanKirimLabel = $currentUser->isSuperAdmin() ? 'Total Rujukan' : 'Rujukan Dikirim';
    $rujukanKirimCount = $currentUser->isSuperAdmin()
        ? \App\Models\Rujukan::count()
        : \App\Models\Rujukan::where('rumah_sakit_asal_id', $currentUser->rumah_sakit_id)->count();
    $rujukanTerimaLabel = $currentUser->isSuperAdmin() ? 'Total Rumah Sakit' : 'Rujukan Diterima';
    $rujukanTerimaCount = $currentUser->isSuperAdmin()
        ? \App\Models\RumahSakit::count()
        : \App\Models\Rujukan::where('rumah_sakit_tujuan_id', $currentUser->rumah_sakit_id)->count();
@endphp

{{-- STYLE KHUSUS DASHBOARD --}}
<style>
  .hero-title{font-weight:800;letter-spacing:.2px}
  .metric{border:0;border-radius:20px;overflow:hidden;transition:.2s box-shadow}
  .metric:hover{box-shadow:0 10px 24px rgba(0,0,0,.08)}
  .metric .cap{font-weight:600;opacity:.9}
  .metric .value{font-weight:800;font-size:48px;line-height:1}
  .grad-blue{background:linear-gradient(135deg,#1d4ed8,#2563eb)}
  .grad-green{background:linear-gradient(135deg,#15803d,#16a34a)}
  .grad-amber{background:linear-gradient(135deg,#ca8a04,#f59e0b)}
  .glass{backdrop-filter:blur(4px);background:rgba(255,255,255,.65)}
  .qcard{border:1px solid #e9ecef;border-radius:16px;transition:.2s transform,.2s box-shadow}
  .qcard:hover{transform:translateY(-2px);box-shadow:0 10px 22px rgba(0,0,0,.07)}
  .qicon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center}
  .qicon.blue{background:#e7f0ff;color:#1d4ed8}
  .qicon.green{background:#eaf7ef;color:#15803d}
  .qicon.purple{background:#efe7ff;color:#6d28d9}
  .qicon.gray{background:#f0f2f5;color:#374151}
  .section-title{font-weight:700}
</style>

<div class="container">

  {{-- HERO --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="hero-title mb-1"><i class="fas fa-hospital-user me-2 text-primary"></i>Dashboard</h1>
      <div class="text-muted">Ringkasan aktivitas & statistik {{ $tahun }}</div>
    </div>
  </div>

  {{-- METRICS --}}
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card metric grad-blue text-white h-100">
        <div class="card-body p-4 d-flex align-items-center justify-content-between">
          <div>
            <div class="cap">Total Pasien</div>
            <div class="value mt-1">{{ \App\Models\Pasien::count() }}</div>
          </div>
          <div class="qicon bg-white" style="color:#1d4ed8"><i class="fas fa-user-injured fa-lg"></i></div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card metric grad-green text-white h-100">
        <div class="card-body p-4 d-flex align-items-center justify-content-between">
          <div>
            <div class="cap">{{ $rujukanKirimLabel }}</div>
            <div class="value mt-1">{{ $rujukanKirimCount }}</div>
          </div>
          <div class="qicon bg-white" style="color:#15803d"><i class="fas fa-paper-plane fa-lg"></i></div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card metric grad-amber text-white h-100">
        <div class="card-body p-4 d-flex align-items-center justify-content-between">
          <div>
            <div class="cap">{{ $rujukanTerimaLabel }}</div>
            <div class="value mt-1">{{ $rujukanTerimaCount }}</div>
          </div>
          <div class="qicon bg-white" style="color:#b45309"><i class="fas fa-inbox fa-lg"></i></div>
        </div>
      </div>
    </div>
  </div>

  {{-- QUICK ACTIONS --}}
  <div class="mt-5">
    <h5 class="section-title mb-3"><i class="fas fa-folder-open me-2 text-warning"></i>Menu Utama</h5>
    <div class="row g-3">

      @if(Auth::user()->canAccessClinical())
      <div class="col-md-6">
        <a href="{{ route('pasien.index') }}" class="text-decoration-none text-reset">
          <div class="qcard p-3 d-flex align-items-center h-100">
            <div class="qicon blue me-3"><i class="fas fa-user-injured"></i></div>
            <div>
              <div class="fw-semibold">Kelola Pasien</div>
              <small class="text-muted">Tambah, ubah, dan telusuri data pasien</small>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6">
        <a href="{{ route('rujukan.index') }}" class="text-decoration-none text-reset">
          <div class="qcard p-3 d-flex align-items-center h-100">
            <div class="qicon green me-3"><i class="fas fa-exchange-alt"></i></div>
            <div>
              <div class="fw-semibold">Kelola Rujukan</div>
              <small class="text-muted">Buat rujukan & pantau statusnya</small>
            </div>
          </div>
        </a>
      </div>
      @endif

      @if(Auth::user()->canManageHospitals())
      <div class="col-md-6">
        <a href="{{ route('rumahsakit.index') }}" class="text-decoration-none text-reset">
          <div class="qcard p-3 d-flex align-items-center h-100">
            <div class="qicon purple me-3"><i class="fas fa-hospital"></i></div>
            <div>
              <div class="fw-semibold">Kelola Rumah Sakit</div>
              <small class="text-muted">Manajemen fasilitas mitra</small>
            </div>
          </div>
        </a>
      </div>
      @endif

      @if(Auth::user()->canManageUsers())
      <div class="col-md-6">
        <a href="{{ route('users.index') }}" class="text-decoration-none text-reset">
          <div class="qcard p-3 d-flex align-items-center h-100">
            <div class="qicon gray me-3"><i class="fas fa-users"></i></div>
            <div>
              <div class="fw-semibold">Kelola Pengguna</div>
              <small class="text-muted">Hak akses & akun pengguna</small>
            </div>
          </div>
        </a>
      </div>
      @endif
    </div>
  </div>

  {{-- CHART --}}
  <div class="mt-5">
    <h5 class="section-title mb-3"><i class="fas fa-chart-line me-2 text-info"></i>Statistik Per Bulan ({{ $tahun }})</h5>
    <div class="card shadow-sm glass">
      <div class="card-body">
        <canvas id="chartPasienRujukan" height="110"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function () {
    const ctx = document.getElementById('chartPasienRujukan').getContext('2d');

    // Gradien lembut untuk area
    const gradBlue  = ctx.createLinearGradient(0, 0, 0, 240);
    gradBlue.addColorStop(0, 'rgba(37,99,235,.35)');
    gradBlue.addColorStop(1, 'rgba(37,99,235,0)');

    const gradGreen = ctx.createLinearGradient(0, 0, 0, 240);
    gradGreen.addColorStop(0, 'rgba(22,163,74,.35)');
    gradGreen.addColorStop(1, 'rgba(22,163,74,0)');

    const data = {
      labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
      datasets: [
        {
          label: 'Pasien Baru',
          data: @json($seriesPasien),
          borderColor: '#2563eb',
          backgroundColor: gradBlue,
          pointRadius: 3,
          pointHoverRadius: 5,
          tension: .35,
          fill: true
        },
        {
          label: 'Rujukan',
          data: @json($seriesRujukan),
          borderColor: '#16a34a',
          backgroundColor: gradGreen,
          pointRadius: 3,
          pointHoverRadius: 5,
          tension: .35,
          fill: true
        }
      ]
    };

    new Chart(ctx, {
      type: 'line',
      data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            mode: 'index',
            intersect: false,
            callbacks: {
              label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y}`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,.06)', borderDash:[4,4] },
            ticks: { precision:0 }
          },
          x: {
            grid: { display:false }
          }
        },
        interaction: { mode:'nearest', intersect:false }
      }
    });
  })();
</script>
