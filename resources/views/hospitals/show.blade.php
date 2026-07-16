@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Detail Rumah Sakit</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hospitals.index') }}" class="text-decoration-none">Data Rumah Sakit</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4 pb-4">
                    
                    <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                        <div class="rounded-circle bg-info d-flex align-items-center justify-content-center text-white me-3" style="width: 60px; height: 60px; font-size: 28px;">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">{{ $hospital->hospital_name }}</h4>
                            <span class="text-muted"><i class="bi bi-hash"></i> {{ $hospital->hospital_code }}</span>
                        </div>
                        <div class="ms-auto">
                            @if($hospital->status == 'Aktif')
                                <span class="badge bg-success px-4 py-2 fs-6">Aktif</span>
                            @else
                                <span class="badge bg-secondary px-4 py-2 fs-6">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-muted mb-3 border-bottom pb-2">Informasi Kontak</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%" class="text-muted">Nama Direktur</th>
                                    <td class="fw-bold">{{ $hospital->director_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Nomor Telepon</th>
                                    <td>{{ $hospital->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Email</th>
                                    <td>{{ $hospital->email ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5 class="fw-bold text-muted mb-3 border-bottom pb-2">Informasi Tambahan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="35%" class="text-muted">Alamat Lengkap</th>
                                    <td>{{ $hospital->address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Ditambahkan Pada</th>
                                    <td>{{ $hospital->created_at->translatedFormat('d F Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Terakhir Diperbarui</th>
                                    <td>{{ $hospital->updated_at->translatedFormat('d F Y, H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="text-end border-top pt-4 mt-2">
                        <a href="{{ route('hospitals.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">Kembali</a>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('hospitals.edit', $hospital->id) }}" class="btn btn-warning rounded-pill px-4">
                                <i class="bi bi-pencil me-1"></i> Edit Data
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
