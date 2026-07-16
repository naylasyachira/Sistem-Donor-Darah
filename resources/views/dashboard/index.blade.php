@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Dashboard</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">
                    <h5 class="card-title fw-bold fs-4" style="color: var(--bs-primary);">Selamat Datang, {{ auth()->user()->name }} 👋</h5>
                    <p class="text-muted fs-6">Semoga aktivitas hari ini berjalan lancar.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        @if(auth()->user()->hasRole('admin'))
            <!-- Total Pendonor -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card sales-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Total Pendonor</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-people text-primary fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['total_pendonor']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total User -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card revenue-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Total User</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-person-badge text-success fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['total_user']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Rumah Sakit -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card customers-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Total Rumah Sakit</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-building text-warning fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['total_rumah_sakit']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendonor Aktif -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Pendonor Aktif</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-activity text-info fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['pendonor_aktif']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendonor Laki-laki -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Pendonor Laki-laki</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-secondary bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-gender-male text-secondary fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['pendonor_laki']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendonor Perempuan -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Pendonor Perempuan</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-gender-female text-danger fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['pendonor_perempuan']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        @elseif(auth()->user()->hasRole('petugas'))
            
            <!-- Total Pendonor -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card sales-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Total Pendonor</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-people text-primary fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['total_pendonor']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendonor Hari Ini -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card revenue-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Pendonor Hari Ini</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-calendar-check text-success fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['pendonor_hari_ini']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendonor Aktif -->
            <div class="col-xxl-4 col-md-6 mb-4">
                <div class="card info-card customers-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Pendonor Aktif</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-activity text-info fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['pendonor_aktif']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif(auth()->user()->hasRole('rumah_sakit'))
            
            <!-- Total Permintaan Darah -->
            <div class="col-xxl-3 col-md-6 mb-4">
                <div class="card info-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Total Permintaan</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-droplet-half text-primary fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['total_permintaan']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permintaan Diproses -->
            <div class="col-xxl-3 col-md-6 mb-4">
                <div class="card info-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Diproses</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-hourglass-split text-warning fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['permintaan_diproses']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permintaan Disetujui -->
            <div class="col-xxl-3 col-md-6 mb-4">
                <div class="card info-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Disetujui</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-check-circle text-success fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['permintaan_disetujui']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permintaan Ditolak -->
            <div class="col-xxl-3 col-md-6 mb-4">
                <div class="card info-card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Ditolak</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-x-circle text-danger fs-3"></i>
                            </div>
                            <div class="ps-2">
                                <h6 class="fs-3 fw-bold mb-0">{{ number_format($stats['permintaan_ditolak']) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    </div>
</section>
@endsection
