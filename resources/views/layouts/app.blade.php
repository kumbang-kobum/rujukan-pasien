<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · Rujukan Pasien</title>

    <link rel="preload" as="image" href="{{ asset('images/bg-app.webp') }}">
    {{-- Vendor --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e0e6ad5f.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @stack('styles')

    <style>
        :root{
            --brand:#2563eb; --brand-2:#1d4ed8; --success:#16a34a; --warn:#f59e0b;
            --bg-overlay: rgba(255,255,255,.70);
        }
        /*body{background:linear-gradient(180deg,#f8fafc 0,#f6f8fb 100%);}*/
        html,body{height:100%;background:transparent;}
        .page-container{max-width:1280px}
        
        /* ===== Background blur full screen ===== */
       .site-bg{
         position: fixed;
         inset: 0;                                /* top/right/bottom/left = 0 */
         background: center / cover no-repeat;
         background-attachment: fixed;
         filter: blur(5px);
         transform: scale(1.06);                  /* hindari tepi blur terlihat */
         z-index: -1;                             /* di belakang semua konten */
         pointer-events: none;
       }
       
       .site-bg::after{
         content: "";
         position: absolute; inset: 0;
         background: var(--bg-overlay);           /* lapisan agar teks tetap terbaca */
       }
       
       @media (max-width: 768px){
         .site-bg{ filter: blur(8px); }
       }

        /* Navbar */
        /* ===== NAVBAR: hijau transparan + kontras kanan ===== */
        .navbar-glass{
          backdrop-filter: blur(6px) saturate(150%);
          -webkit-backdrop-filter: blur(6px) saturate(150%);
          /* sedikit lebih pekat di sisi kanan agar elemen kanan terlihat */
          background: linear-gradient(90deg,
            rgba(16,185,129,0.16) 0%,
            rgba(16,185,129,0.20) 55%,
            rgba(6,95,70,0.26)    100%
          );
          border-bottom: 1px solid rgba(16,185,129,0.28);
          box-shadow: 0 2px 12px rgba(0,0,0,.08);
        }
        
        /* Warna teks link */
        .navbar-light .navbar-nav .nav-link{
          color:#064e3b;                 /* emerald-900 */
          /*padding:.55rem .9rem;*/
          border-radius:999px;
          border:1px solid transparent;  /* utk efek active */
        }
        
        /* Hover ringan */
        .navbar-light .navbar-nav .nav-link:hover{
          color:#0f766e;                 /* teal-700 */
          background: rgba(255,255,255,.25);
          border-color: rgba(255,255,255,.45);
          backdrop-filter: blur(6px);
        }
        
        /* ===== “Glass border” untuk item aktif ===== */
        .navbar-light .navbar-nav .nav-link.active{
          color:#064e3b !important;
          background: rgba(255,255,255,.35);
          border-color: rgba(255,255,255,.65);
          box-shadow:
            inset 0 1px 0 rgba(255,255,255,.45),
            0 2px 8px rgba(0,0,0,.08);
          backdrop-filter: blur(6px) saturate(140%);
        }
        
        /* ===== Tombol user kanan: varian glass yang kontras ===== */
        .btn-glass-emerald{
          color:#064e3b;                                   /* teks gelap */
          border:1px solid rgba(6,95,70,.45);
          background: rgba(255,255,255,.55);               /* ada isi, jadi kebaca */
          backdrop-filter: blur(6px) saturate(140%);
          -webkit-backdrop-filter: blur(6px) saturate(140%);
          box-shadow: 0 1px 8px rgba(0,0,0,.08);
        }
        .btn-glass-emerald:hover{
          background: rgba(6,95,70,.12);
          border-color: rgba(6,95,70,.6);
        }
        
        /* Dropdown juga diberi efek glass biar serasi */
        .dropdown-menu{
          background: rgba(255,255,255,.88);
          backdrop-filter: blur(10px);
          -webkit-backdrop-filter: blur(10px);
          border: 1px solid rgba(6,95,70,.15);
        }

        /*.navbar-glass{backdrop-filter:blur(6px); background:rgba(17,24,39,.92)}*/
        /*.navbar .nav-link{padding:.55rem .9rem; border-radius:999px}*/
        /*.navbar .nav-link.active, .navbar .nav-link:hover{background:rgba(255,255,255,.12)}*/
        .avatar{width:32px;height:32px;border-radius:50%;background:#e5e7eb;display:inline-flex;align-items:center;justify-content:center}

        .brand-logo{ height:32px; width:auto; }

        /* ikon gambar di item menu kiri */
        .nav-icon{ height:18px; width:auto; vertical-align:-2px; }

        /* (opsional) avatar foto user di kanan */
        .avatar-img{ width:32px; height:32px; object-fit:cover; border-radius:50%; }

        /* Alerts */
        .alert-float{position:sticky;top:1rem;z-index:1031}
        
        .chip{padding:.25rem .5rem;border:1px solid #ddd;border-radius:999px}
        .chip .x{margin-left:.4rem;cursor:pointer}

        /* Utilities */
        .multiline{white-space:pre-line} /* hormati \n sebagai paragraf */
        .select2-container .select2-selection--single{height:38px;padding:.35rem .75rem;border:1px solid #ced4da;border-radius:.375rem}
        .select2-selection__arrow{height:36px;right:8px}
        footer{color:#6b7280}
    </style>
</head>
<body>
    <div class="site-bg" style="background-image:url('{{ asset('images/bg-app.webp') }}')"></div>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light navbar-glass shadow-sm sticky-top">
        <div class="container-fluid page-container">
           <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo-rujukan.png') }}" alt="Rujukan Pasien" class="brand-logo">
            {{-- kalau mau tanpa teks, hapus span di bawah --}}
            <!-- <span class="d-none d-sm-inline">Rujukan Pasien</span> -->
            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                {{-- Left Menu --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- Semua user --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>

                    @php
                        $canSeeClinical = Auth::user()->isAdmin() || Auth::user()->isDokter() || Auth::user()->isPerawat();
                    @endphp

                    @if($canSeeClinical)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}" href="{{ route('pasien.index') }}">
                                <i class="fas fa-user-injured me-1"></i> Pasien
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('kunjungan.*') ? 'active' : '' }}" href="{{ route('kunjungan.index') }}">
                                <i class="fas fa-stethoscope me-1"></i> Kunjungan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('soap.*') ? 'active' : '' }}" href="{{ route('soap.index') }}">
                                <i class="fas fa-file-medical me-1"></i> SOAP
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('rujukan.*') ? 'active' : '' }}" href="{{ route('rujukan.index') }}">
                                <i class="fas fa-exchange-alt me-1"></i> Rujukan
                            </a>
                        </li>
                        @if(Auth::user()->isDokter() || Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('konsultasi.*') ? 'active' : '' }}" href="{{ route('konsultasi.index') }}">
                                <i class="fas fa-comment-medical"></i> Konsultasi
                                @if(($konsultasiAttentionCount ?? 0) > 0)
                                    <span class="badge rounded-pill bg-danger ms-1">{{ $konsultasiAttentionCount }}</span>
                                @endif
                            </a>
                        </li>
                        @endif
                    @endif

                    @if(Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('rumahsakit.*') ? 'active' : '' }}" href="{{ route('rumahsakit.index') }}">
                                <i class="fas fa-hospital me-1"></i> Kelola Rumah Sakit
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-users me-1"></i> Kelola Pengguna
                            </a>
                        </li>
                    @endif
                </ul>

                {{-- Right Dropdown --}}
                @auth
                <div class="d-flex align-items-center gap-2">
                  <div class="dropdown">
                    <a class="btn btn-glass-emerald rounded-pill px-3 position-relative" href="#" id="dropdownNotif" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-bell"></i>
                      @if(($unreadNotificationCount ?? 0) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          {{ $unreadNotificationCount }}
                        </span>
                      @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow p-0" style="min-width: 360px;">
                      <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                        <div>
                          <div class="fw-semibold">Notifikasi</div>
                          <div class="small text-muted">Aktivitas konsultasi terbaru</div>
                        </div>
                        @if(($unreadNotificationCount ?? 0) > 0)
                          <form method="POST" action="{{ route('notifications.read-all') }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary">Tandai semua</button>
                          </form>
                        @endif
                      </div>

                      @forelse(($notificationItems ?? collect()) as $notification)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                          @csrf
                          <button type="submit" class="dropdown-item px-3 py-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                              <div class="text-start">
                                <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notifikasi' }}</div>
                                <div class="small text-muted multiline">{{ $notification->data['message'] ?? '-' }}</div>
                              </div>
                              <div class="small text-muted text-nowrap">{{ $notification->created_at?->diffForHumans() }}</div>
                            </div>
                          </button>
                        </form>
                      @empty
                        <div class="px-3 py-4 text-center text-muted small">Belum ada notifikasi.</div>
                      @endforelse
                    </div>
                  </div>

                  <div class="dropdown">
                    <a class="btn btn-glass-emerald rounded-pill px-3 d-flex align-items-center gap-2"
                       href="#" id="dropdownUser" data-bs-toggle="dropdown">
                      <img src="{{ Auth::user()->avatar_url }}" class="avatar-img" alt="Avatar">
                      <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                      <li class="px-3 py-2 small text-muted">
                        <div class="fw-semibold">{{ ucfirst(Auth::user()->role) }}</div>
                        <div>RS {{ Auth::user()->rumahSakit->nama ?? '-' }}</div>
                      </li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-id-card me-2"></i> Profil</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form method="POST" action="{{ route('logout') }}"> @csrf
                          <button class="dropdown-item text-danger" type="submit">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main --}}
    <main class="py-4">
        <div class="container page-container">

            @yield('content')
        </div>
    </main>

    <footer class="py-4">
        <div class="container page-container d-flex justify-content-between small">
            <span>&copy; {{ date('Y') }} Rujukan Pasien</span>
            <span>Rujukan Pasien {{ Illuminate\Foundation\Application::VERSION }}</span>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Inisialisasi Select2 jika elemen diberi class .select2
        $(function(){
            $('.select2').select2({ width: '100%' });
            // Auto-close alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(a => {
                    try { new bootstrap.Alert(a).close(); } catch (e) {}
                })
            }, 4000);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-eye').forEach(function (btn) {
            btn.addEventListener('click', function () {
            const input = document.querySelector(this.dataset.target);
            if (!input) return;
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            this.setAttribute('aria-pressed', String(!showing));
            this.setAttribute('aria-label', showing ? 'Tampilkan password' : 'Sembunyikan password');
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', showing);
                icon.classList.toggle('fa-eye-slash', !showing);
            }
            });
        });
        });
    </script>


    @stack('scripts')
</body>
</html>
