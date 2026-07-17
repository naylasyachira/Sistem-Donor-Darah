@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Detail Permintaan Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blood-requests.index') }}" class="text-decoration-none">Permintaan Darah</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <h5 class="card-title m-0 fw-bold fs-4">Request #{{ $bloodRequest->request_code }}</h5>
                        <div>
                            @if($bloodRequest->status == 'Menunggu')
                                <span class="badge bg-warning text-dark fs-6"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                            @elseif($bloodRequest->status == 'Diproses')
                                <span class="badge bg-info fs-6"><i class="bi bi-arrow-repeat me-1"></i>Diproses</span>
                            @elseif($bloodRequest->status == 'Selesai')
                                <span class="badge bg-success fs-6"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                            @elseif($bloodRequest->status == 'Ditolak')
                                <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Rumah Sakit</div>
                        <div class="col-sm-8">{{ $bloodRequest->hospital->hospital_name ?? '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Tanggal Request</div>
                        <div class="col-sm-8">{{ \Carbon\Carbon::parse($bloodRequest->request_date)->translatedFormat('d F Y') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Golongan Darah</div>
                        <div class="col-sm-8">
                            <span class="fw-bold text-danger">{{ $bloodRequest->blood_type }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Rhesus</div>
                        <div class="col-sm-8">{{ $bloodRequest->rhesus == '+' ? 'Positif (+)' : 'Negatif (-)' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Jumlah Kantong</div>
                        <div class="col-sm-8">{{ $bloodRequest->quantity }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted fw-bold">Catatan</div>
                        <div class="col-sm-8">{{ $bloodRequest->notes ?? '-' }}</div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top gap-2">
                        <a href="{{ route('blood-requests.index') }}" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                        @if(auth()->user()->hasRole(['admin', 'petugas']))
                            <a href="{{ route('blood-requests.edit', $bloodRequest->id) }}" class="btn btn-warning rounded-pill px-4">
                                <i class="bi bi-pencil me-1"></i> Ubah Status
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
