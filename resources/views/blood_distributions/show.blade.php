@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Detail Distribusi Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blood-distributions.index') }}" class="text-decoration-none">Distribusi Darah</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    
                    <h5 class="card-title fw-bold">Informasi Distribusi</h5>
                    
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold text-muted w-25">Kode Distribusi</td>
                            <td>: <span class="fw-bold text-primary">{{ $bloodDistribution->distribution_code }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Kode Permintaan</td>
                            <td>: {{ $bloodDistribution->bloodRequest->request_code ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Rumah Sakit Tujuan</td>
                            <td>: {{ $bloodDistribution->bloodRequest->hospital->hospital_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Nama Kurir</td>
                            <td>: {{ $bloodDistribution->courier_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Golongan Darah</td>
                            <td>: <span class="badge bg-danger">{{ $bloodDistribution->bloodRequest->blood_type ?? '-' }}{{ $bloodDistribution->bloodRequest->rhesus ?? '' }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Jumlah Kantong</td>
                            <td>: {{ $bloodDistribution->bloodRequest->quantity ?? '-' }} Kantong</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Tanggal Distribusi</td>
                            <td>: {{ \Carbon\Carbon::parse($bloodDistribution->distribution_date)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Status</td>
                            <td>: 
                                @if($bloodDistribution->status == 'Diproses')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Diproses</span>
                                @elseif($bloodDistribution->status == 'Dikirim')
                                    <span class="badge bg-info"><i class="bi bi-truck me-1"></i>Dikirim</span>
                                @elseif($bloodDistribution->status == 'Diterima')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Diterima</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <hr class="my-4">

                    <div class="d-flex justify-content-start">
                        <a href="{{ route('blood-distributions.index') }}" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
