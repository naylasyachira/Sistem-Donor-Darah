@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Detail Screening Kesehatan</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('screenings.index') }}" class="text-decoration-none">Screening Kesehatan</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    @if($screening->status == 'Lulus')
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                            <div>
                                <h5 class="alert-heading fw-bold mb-1">Pendonor siap melanjutkan proses donor darah.</h5>
                                <p class="mb-0 small">Kondisi kesehatan pendonor memenuhi standar untuk melakukan donor darah hari ini.</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                            <div>
                                <h5 class="alert-heading fw-bold mb-1">Pendonor belum memenuhi syarat donor.</h5>
                                <p class="mb-0 small">Kondisi kesehatan pendonor tidak memungkinkan untuk melakukan donor darah saat ini. Silakan periksa catatan petugas.</p>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-muted border-bottom pb-2 mb-3">Informasi Pendonor</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%" class="text-muted">Kode Screening</th>
                                    <td><span class="fw-bold text-primary">{{ $screening->screening_code }}</span></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Kode Pendonor</th>
                                    <td>{{ $screening->donor->donor_code ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nama Pendonor</th>
                                    <td>{{ $screening->donor->nama_lengkap ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tanggal Screening</th>
                                    <td>{{ \Carbon\Carbon::parse($screening->screening_date)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Status Screening</th>
                                    <td>
                                        @if($screening->status == 'Lulus')
                                            <span class="badge bg-success px-3 py-2">Lulus</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2">Tidak Lulus</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5 class="fw-bold text-muted border-bottom pb-2 mb-3">Rekam Medis (Vitals)</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%" class="text-muted">Tekanan Darah</th>
                                    <td>{{ $screening->tekanan_darah }} mmHg</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Berat Badan</th>
                                    <td>{{ $screening->berat_badan }} Kg</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Tinggi Badan</th>
                                    <td>{{ $screening->tinggi_badan }} cm</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Hemoglobin</th>
                                    <td>{{ $screening->hemoglobin }} g/dL</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Suhu Tubuh</th>
                                    <td>{{ $screening->suhu_tubuh }} °C</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Denyut Nadi</th>
                                    <td>{{ $screening->denyut_nadi }} bpm</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="fw-bold text-muted border-bottom pb-2 mb-3">Informasi Tambahan</h5>
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold mb-2">Catatan Petugas:</h6>
                                <p class="mb-0 {{ $screening->notes ? '' : 'text-muted fst-italic' }}">
                                    {{ $screening->notes ?? 'Tidak ada catatan.' }}
                                </p>
                            </div>
                            <div class="mt-3 text-muted small">
                                <i class="bi bi-clock me-1"></i> Data dibuat pada: {{ $screening->created_at->translatedFormat('d F Y H:i') }} oleh {{ $screening->user->name ?? 'Sistem' }}
                            </div>
                        </div>
                    </div>

                    <div class="text-end border-top pt-4">
                        <a href="{{ route('screenings.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">Kembali</a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('screenings.edit', $screening->id) }}" class="btn btn-warning rounded-pill px-4">
                                <i class="bi bi-pencil me-1"></i> Edit Screening
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
