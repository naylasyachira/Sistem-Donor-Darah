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
        <!-- Sales Card -->
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card sales-card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Total Donors</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-people text-primary fs-3"></i>
                        </div>
                        <div class="ps-2">
                            <h6 class="fs-3 fw-bold mb-0">145</h6>
                            <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card revenue-card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Blood Stock</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-droplet text-danger fs-3"></i>
                        </div>
                        <div class="ps-2">
                            <h6 class="fs-3 fw-bold mb-0">3,264</h6>
                            <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card customers-card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Hospitals</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-building text-warning fs-3"></i>
                        </div>
                        <div class="ps-2">
                            <h6 class="fs-3 fw-bold mb-0">12</h6>
                            <span class="text-danger small pt-1 fw-bold">1%</span> <span class="text-muted small pt-2 ps-1">decrease</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distributions Card -->
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card distributions-card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title text-muted text-uppercase fs-6 mb-3">Distributions</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-truck text-info fs-3"></i>
                        </div>
                        <div class="ps-2">
                            <h6 class="fs-3 fw-bold mb-0">84</h6>
                            <span class="text-success small pt-1 fw-bold">5%</span> <span class="text-muted small pt-2 ps-1">increase</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
