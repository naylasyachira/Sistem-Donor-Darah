@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Profil Saya</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Profil</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=F8D7DA&color=DC3545&size=120" alt="Profile" class="rounded-circle mb-3 shadow-sm">
                    <h2 class="fs-4 fw-bold" style="color: #012970;">{{ auth()->user()->name }}</h2>
                    @php
                        $roleName = 'User';
                        if(Str::contains(auth()->user()->email, 'admin')) $roleName = 'Administrator';
                        elseif(Str::contains(auth()->user()->email, 'petugas')) $roleName = 'Petugas PMI';
                        elseif(Str::contains(auth()->user()->email, 'rs')) $roleName = 'Rumah Sakit';
                    @endphp
                    <h3 class="fs-6 text-muted">{{ $roleName }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    <h5 class="card-title fw-bold mb-4 fs-5" style="color: #012970;">Detail Profil</h5>
                    
                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted fw-bold">Nama Lengkap</div>
                        <div class="col-lg-9 col-md-8">{{ auth()->user()->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted fw-bold">Email</div>
                        <div class="col-lg-9 col-md-8">{{ auth()->user()->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted fw-bold">Role</div>
                        <div class="col-lg-9 col-md-8">{{ $roleName }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label text-muted fw-bold">Bergabung Sejak</div>
                        <div class="col-lg-9 col-md-8">{{ auth()->user()->created_at->format('d F Y') }}</div>
                    </div>

                    <div class="text-start mt-4">
                        <a href="{{ route('settings') }}" class="btn btn-primary rounded-pill px-4">Edit Profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
