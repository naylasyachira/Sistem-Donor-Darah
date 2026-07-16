@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Detail User</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-decoration-none">User Management</a></li>
            <li class="breadcrumb-item active">Detail User</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4 text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=F8D7DA&color=DC3545&size=120" alt="Profile" class="rounded-circle mb-3 shadow-sm">
                    <h5 class="fw-bold fs-4 mb-1" style="color: #012970;">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge bg-success px-3 py-2 rounded-pill mb-4">Aktif</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    <h5 class="card-title fw-bold mb-4 fs-5" style="color: #012970;">Informasi Akun</h5>
                    
                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-4 label fw-bold text-muted">Nama Lengkap</div>
                        <div class="col-lg-8 col-md-8">{{ $user->name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-4 label fw-bold text-muted">Email</div>
                        <div class="col-lg-8 col-md-8">{{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-4 label fw-bold text-muted">Role</div>
                        <div class="col-lg-8 col-md-8">{{ $user->role ? $user->role->display_name : '-' }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-4 label fw-bold text-muted">Terdaftar Sejak</div>
                        <div class="col-lg-8 col-md-8">{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                        @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning rounded-pill px-4 ms-2 text-white">Edit User</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
