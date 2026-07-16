@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Detail Donor Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('donations.index') }}" class="text-decoration-none">Donor Darah</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    @if($donation->status == 'Berhasil')
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                            <div>
                                <h5 class="alert-heading fw-bold mb-1">Donor berhasil dilakukan.</h5>
                                <p class="mb-0 small">Proses donor darah telah diselesaikan dengan aman sesuai standar operasional.</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-x-circle-fill me-2 fs-4"></i>
                            <div>
                                <h5 class="alert-heading fw-bold mb-1">Proses donor dibatalkan.</h5>
                                <p class="mb-0 small">Pelaksanaan donor darah tidak dapat dilanjutkan. Silakan lihat catatan petugas untuk detail pembatalan.</p>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-muted border-bottom pb-2 mb-3">Informasi Utama</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%" class="text-muted">Kode Donor</th>
                                    <td><span class="fw-bold text-primary">{{ $donation->donation_code }}</span></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nomor Screening</th>
                                    <td>{{ $donation->screening->screening_code ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Kode Pendonor</th>
                                    <td>{{ $donation->donor->donor_code ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nama Pendonor</th>
                                    <td>{{ $donation->donor->nama_lengkap ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tanggal Donor</th>
                                    <td>{{ \Carbon\Carbon::parse($donation->donation_date)->translatedFormat('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5 class="fw-bold text-muted border-bottom pb-2 mb-3">Rincian Medis</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%" class="text-muted">Golongan Darah</th>
                                    <td><span class="fw-bold text-danger">{{ $donation->blood_type }}</span></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Rhesus</th>
                                    <td>{{ $donation->rhesus }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Volume Darah</th>
                                    <td>{{ $donation->blood_volume }} ml</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status Donor</th>
                                    <td>
                                        @if($donation->status == 'Berhasil')
                                            <span class="badge bg-success px-3 py-2">Berhasil</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2">Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="fw-bold text-muted border-bottom pb-2 mb-3">Catatan Administrasi</h5>
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold mb-2">Catatan Petugas:</h6>
                                <p class="mb-0 {{ $donation->notes ? '' : 'text-muted fst-italic' }}">
                                    {{ $donation->notes ?? 'Tidak ada catatan.' }}
                                </p>
                            </div>
                            <div class="mt-3 text-muted small">
                                <i class="bi bi-clock me-1"></i> Data dibuat pada: {{ $donation->created_at->translatedFormat('d F Y H:i') }} oleh {{ $donation->user->name ?? 'Sistem' }}
                            </div>
                        </div>
                    </div>

                    <div class="text-end border-top pt-4">
                        <a href="{{ route('donations.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">Kembali</a>
                        @if(auth()->user()->hasRole(['admin', 'petugas']))
                            <a href="{{ route('donations.edit', $donation->id) }}" class="btn btn-warning rounded-pill px-4">
                                <i class="bi bi-pencil me-1"></i> Edit Donor
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
