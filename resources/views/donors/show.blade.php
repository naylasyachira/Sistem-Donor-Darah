@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Detail Pendonor</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('donors.index') }}" class="text-decoration-none">Master Pendonor</a></li>
            <li class="breadcrumb-item active">Detail Pendonor</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($donor->nama_lengkap) }}&background=F8D7DA&color=DC3545&size=120" alt="Avatar" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <h4 class="fw-bold text-center" style="color: #012970;">{{ $donor->nama_lengkap }}</h4>
                    <h6 class="text-muted text-center mb-3">{{ $donor->donor_code }}</h6>
                    
                    <div class="d-flex gap-2">
                        @php
                            $golDarahClass = 'bg-secondary';
                            $golDarahStyle = '';
                            if($donor->golongan_darah == 'A') $golDarahClass = 'bg-danger';
                            elseif($donor->golongan_darah == 'B') $golDarahClass = 'bg-primary';
                            elseif($donor->golongan_darah == 'AB') {
                                $golDarahClass = '';
                                $golDarahStyle = 'background-color: #6f42c1; color: white;';
                            }
                            elseif($donor->golongan_darah == 'O') $golDarahClass = 'bg-success';
                        @endphp
                        <span class="badge {{ $golDarahClass }} fs-6 px-3 py-2" style="{{ $golDarahStyle }}">Golongan {{ $donor->golongan_darah }} ({{ $donor->rhesus == '+' ? 'Positif' : 'Negatif' }})</span>
                        
                        @if($donor->status == 'aktif')
                            <span class="badge bg-success fs-6 px-3 py-2">Aktif</span>
                        @else
                            <span class="badge bg-secondary fs-6 px-3 py-2">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#profile-overview">Informasi Lengkap</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content pt-4">
                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label text-muted fw-bold">NIK</div>
                                <div class="col-lg-9 col-md-8">{{ $donor->nik }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label text-muted fw-bold">Jenis Kelamin</div>
                                <div class="col-lg-9 col-md-8">{{ $donor->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label text-muted fw-bold">Umur</div>
                                <div class="col-lg-9 col-md-8">{{ \Carbon\Carbon::parse($donor->tanggal_lahir)->age }} Tahun</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label text-muted fw-bold">Tanggal Lahir</div>
                                <div class="col-lg-9 col-md-8">{{ \Carbon\Carbon::parse($donor->tanggal_lahir)->translatedFormat('d F Y') }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label text-muted fw-bold">Nomor HP</div>
                                <div class="col-lg-9 col-md-8">{{ $donor->no_hp }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label text-muted fw-bold">Alamat</div>
                                <div class="col-lg-9 col-md-8">{{ $donor->alamat ?: '-' }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-4 label text-muted fw-bold">Tanggal Dibuat</div>
                                <div class="col-lg-9 col-md-8">{{ $donor->created_at->translatedFormat('d F Y H:i') }}</div>
                            </div>

                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('donors.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">Kembali</a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('donors.edit', $donor->id) }}" class="btn btn-warning rounded-pill px-4">Edit Pendonor</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
