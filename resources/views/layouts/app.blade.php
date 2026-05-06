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
        .notification-menu{min-width:340px;max-width:380px}
        .notification-item{white-space:normal}
        .notification-item.unread{
          background: rgba(37,99,235,.08);
          border-left: 3px solid rgba(37,99,235,.8);
        }
        .notification-badge{
          position:absolute;
          top:-4px;
          right:-4px;
          min-width:18px;
          height:18px;
          padding:0 5px;
          border-radius:999px;
          font-size:.7rem;
          line-height:18px;
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
                        $currentUser = Auth::user();
                        $canSeeClinical = $currentUser->canAccessClinical();
                        $canSeeConsultation = $currentUser->isDokter() || $currentUser->isAdminRs();
                        $isClinicalActive = request()->routeIs('pasien.*')
                            || request()->routeIs('kunjungan.*')
                            || request()->routeIs('soap.*')
                            || request()->routeIs('rujukan.*')
                            || request()->routeIs('konsultasi.*');
                        $isAdminRsActive = request()->routeIs('users.*') || request()->routeIs('admin.password.*');
                        $isPlatformActive = request()->routeIs('rumahsakit.*')
                            || ($currentUser->isSuperAdmin() && request()->routeIs('users.*'));
                    @endphp

                    @if($canSeeClinical)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $isClinicalActive ? 'active' : '' }}" href="#" id="menuPelayananRs" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-briefcase-medical me-1"></i> Pelayanan RS
                            </a>
                            <ul class="dropdown-menu shadow" aria-labelledby="menuPelayananRs">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('pasien.*') ? 'active' : '' }}" href="{{ route('pasien.index') }}">
                                        <i class="fas fa-user-injured me-2"></i> Pasien
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('kunjungan.*') ? 'active' : '' }}" href="{{ route('kunjungan.index') }}">
                                        <i class="fas fa-stethoscope me-2"></i> Kunjungan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('soap.*') ? 'active' : '' }}" href="{{ route('soap.index') }}">
                                        <i class="fas fa-file-medical me-2"></i> SOAP
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('rujukan.*') ? 'active' : '' }}" href="{{ route('rujukan.index') }}">
                                        <i class="fas fa-exchange-alt me-2"></i> Rujukan
                                    </a>
                                </li>
                                @if($canSeeConsultation)
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('konsultasi.*') ? 'active' : '' }}" href="{{ route('konsultasi.index') }}">
                                            <i class="fas fa-comment-medical me-2"></i> Konsultasi Dokter
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if($currentUser->isAdminRs())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $isAdminRsActive ? 'active' : '' }}" href="#" id="menuAdminRs" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-shield me-1"></i> Administrasi RS
                            </a>
                            <ul class="dropdown-menu shadow" aria-labelledby="menuAdminRs">
                                <li class="px-3 py-2 small text-muted">
                                    <div class="fw-semibold">{{ $currentUser->rumahSakit->nama ?? 'Rumah sakit saya' }}</div>
                                    <div>Kelola pengguna internal RS</div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fas fa-users me-2"></i> Pengguna RS
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.password.*') ? 'active' : '' }}" href="{{ route('admin.password.edit') }}">
                                        <i class="fas fa-key me-2"></i> Password Admin
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if($currentUser->isSuperAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $isPlatformActive ? 'active' : '' }}" href="#" id="menuPlatform" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-network-wired me-1"></i> Platform
                            </a>
                            <ul class="dropdown-menu shadow" aria-labelledby="menuPlatform">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('rumahsakit.*') ? 'active' : '' }}" href="{{ route('rumahsakit.index') }}">
                                        <i class="fas fa-hospital me-2"></i> Master Rumah Sakit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fas fa-users-cog me-2"></i> Pengguna Platform
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

                {{-- Right Dropdown --}}
                @auth
                <div class="d-flex align-items-center gap-2">
                  @if(Auth::user()->isDokter() || Auth::user()->isAdminRs())
                  <div class="dropdown">
                    <a class="btn btn-glass-emerald rounded-pill px-3 position-relative"
                       href="#" id="dropdownNotif" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-bell"></i>
                      @if(($konsultasiNotificationCount ?? 0) > 0)
                        <span class="badge bg-danger notification-badge">{{ $konsultasiNotificationCount > 9 ? '9+' : $konsultasiNotificationCount }}</span>
                      @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow notification-menu p-0" aria-labelledby="dropdownNotif">
                      <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Notifikasi Konsultasi</span>
                        @if(($konsultasiNotificationCount ?? 0) > 0)
                          <span class="badge bg-danger">{{ $konsultasiNotificationCount }} baru</span>
                        @endif
                      </div>
                      @forelse(($konsultasiNotifications ?? collect()) as $notification)
                        <a
                          href="{{ $notification->data['url'] ?? route('konsultasi.index') }}"
                          class="dropdown-item notification-item px-3 py-2 {{ is_null($notification->read_at) ? 'unread' : '' }}"
                        >
                          <div class="fw-semibold small mb-1">{{ $notification->data['judul'] ?? 'Konsultasi' }}</div>
                          <div class="small text-muted mb-1">{{ $notification->data['message'] ?? 'Ada pembaruan konsultasi.' }}</div>
                          <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                        </a>
                      @empty
                        <div class="px-3 py-3 small text-muted">Belum ada notifikasi konsultasi.</div>
                      @endforelse
                      <div class="border-top px-3 py-2 text-center">
                        <a href="{{ route('konsultasi.index') }}" class="small text-decoration-none">Buka daftar konsultasi</a>
                      </div>
                    </div>
                  </div>
                  @endif
                  <div class="dropdown">
                    <a class="btn btn-glass-emerald rounded-pill px-3 d-flex align-items-center gap-2"
                       href="#" id="dropdownUser" data-bs-toggle="dropdown">
                      <img src="{{ Auth::user()->avatar_url }}" class="avatar-img" alt="Avatar">
                      <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                      <li class="px-3 py-2 small text-muted">
                        <div class="fw-semibold">{{ Auth::user()->role_label }}</div>
                        <div>{{ Auth::user()->rumahSakit ? 'RS '.Auth::user()->rumahSakit->nama : 'Platform' }}</div>
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
