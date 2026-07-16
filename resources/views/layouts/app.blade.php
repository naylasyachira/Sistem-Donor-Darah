<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedPulse - Connecting Lives Through Every Drop</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #DC3545;
            --bs-primary-rgb: 220, 53, 69;
            --bs-secondary: #B02A37;
            --bs-secondary-rgb: 176, 42, 55;
            --bs-accent: #F8D7DA;
        }
        body {
            background-color: #f6f9ff;
            color: #444444;
            font-family: "Open Sans", sans-serif;
        }
        
        /* Navbar Styles */
        .header {
            transition: all 0.5s;
            z-index: 997;
            height: 60px;
            box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1);
            background-color: #fff;
            padding-left: 20px;
        }
        .header .logo {
            line-height: 1;
        }
        .header .logo span {
            font-size: 26px;
            font-weight: 700;
            color: var(--bs-primary);
            font-family: "Nunito", sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            width: 260px;
            z-index: 996;
            transition: all 0.3s;
            padding: 20px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #aab7cf transparent;
            box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1);
            background-color: #fff;
        }
        .sidebar-nav {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .sidebar-nav li {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .sidebar-nav .nav-item {
            margin-bottom: 5px;
        }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            font-size: 15px;
            font-weight: 600;
            color: #4154f1;
            transition: 0.3s;
            background: #f6f9ff;
            padding: 10px 15px;
            border-radius: 4px;
        }
        .sidebar-nav .nav-link i {
            font-size: 16px;
            margin-right: 10px;
            color: #4154f1;
        }
        .sidebar-nav .nav-link.collapsed {
            color: #012970;
            background: #fff;
        }
        .sidebar-nav .nav-link.collapsed i {
            color: #899bbd;
        }
        .sidebar-nav .nav-link:hover {
            color: var(--bs-primary);
            background: var(--bs-accent);
        }
        .sidebar-nav .nav-link:hover i {
            color: var(--bs-primary);
        }

        /* Main Content */
        #main {
            margin-top: 60px;
            padding: 20px 30px;
            transition: all 0.3s;
        }
        @media (min-width: 1200px) {
            #main, #footer {
                margin-left: 260px;
            }
        }

        /* Footer */
        .footer {
            padding: 20px 0;
            font-size: 14px;
            transition: all 0.3s;
            border-top: 1px solid #cddfff;
        }
        .footer .copyright {
            text-align: center;
            color: #012970;
        }
    </style>
</head>
<body>
    
    @auth
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top flex-row align-items-center d-flex">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center text-decoration-none">
                <i class="bi bi-droplet-fill text-danger fs-3 me-2"></i>
                <span class="d-none d-lg-block">RedPulse</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn ms-4 fs-3" style="cursor: pointer;"></i>
        </div>

        <nav class="header-nav ms-auto pe-4">
            <ul class="d-flex align-items-center m-0">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0 text-decoration-none text-dark" role="button" style="cursor:pointer;" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=F8D7DA&color=DC3545" alt="Profile" class="rounded-circle" width="36" height="36">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile" style="min-width: 240px;">
                        @php
                            $roleName = auth()->user()->role ? auth()->user()->role->display_name : 'User';
                        @endphp
                        <li class="dropdown-header text-center py-2">
                            <h6 class="mb-1 fw-bold">{{ auth()->user()->name }}</h6>
                            <span class="text-muted small">{{ $roleName }}</span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                <i class="bi bi-person me-2 fs-5"></i>
                                <span>Profil Saya</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('settings') }}">
                                <i class="bi bi-gear me-2 fs-5"></i>
                                <span>Pengaturan Akun</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="confirmLogout(event)">
                                <i class="bi bi-box-arrow-right me-2 fs-5"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Admin & Petugas Items -->
            @if(auth()->user()->hasRole(['admin', 'petugas']))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-person"></i>
                    <span>User Management</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('donors.*') ? '' : 'collapsed' }}" href="{{ route('donors.index') }}">
                    <i class="bi bi-droplet-half"></i>
                    <span>Master Pendonor</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('screenings.*') ? '' : 'collapsed' }}" href="{{ route('screenings.index') }}">
                    <i class="bi bi-heart-pulse"></i>
                    <span>Screening Kesehatan</span>
                </a>
            </li>
            @endif
        </ul>
    </aside>
    @endauth

    <main id="{{ auth()->check() ? 'main' : '' }}" class="{{ auth()->check() ? 'main' : 'w-100' }}">
        @yield('content')
    </main>

    @auth
    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>RedPulse</span></strong>. All Rights Reserved.
        </div>
        <div class="text-center mt-1 text-muted" style="font-size: 12px;">
            Connecting Lives Through Every Drop
        </div>
    </footer>
    @endauth

    <!-- Bootstrap 5 JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            let successMsg = {!! json_encode(session('success')) !!};
            let successTitle = 'MANTAP!';
            
            if (successMsg.includes('masuk')) {
                successTitle = 'Selamat Datang!';
            } else if (successMsg.includes('keluar')) {
                successTitle = 'Sampai Jumpa!';
            } else if (successMsg.includes('pendonor')) {
                successTitle = 'Berhasil';
            }

            Swal.fire({
                icon: 'success',
                title: successTitle,
                text: successMsg,
                timer: 2000,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                backdrop: `rgba(0,0,0,0.4)`
            });
        @endif
        
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: {!! json_encode(session('error')) !!},
                showConfirmButton: true,
                backdrop: `rgba(0,0,0,0.4)`
            });
        @endif
        
        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: {!! json_encode(session('warning')) !!},
                showConfirmButton: true,
                backdrop: `rgba(0,0,0,0.4)`
            });
        @endif
        
        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: {!! json_encode(session('info')) !!},
                showConfirmButton: true,
                backdrop: `rgba(0,0,0,0.4)`
            });
        @endif

        function confirmLogout(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Keluar dari aplikasi?',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#DC3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Logout',
                cancelButtonText: 'Batal',
                backdrop: `rgba(0,0,0,0.4)`
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('logout') }}";
                }
            });
        }
    </script>
</body>
</html>
