@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Ubah Status Distribusi Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blood-distributions.index') }}" class="text-decoration-none">Distribusi Darah</a></li>
            <li class="breadcrumb-item active">Ubah Status</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('blood-distributions.update', $bloodDistribution->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Kode Distribusi</label>
                            <div class="fs-5 fw-bold text-primary">{{ $bloodDistribution->distribution_code }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Rumah Sakit Tujuan</label>
                            <div>{{ $bloodDistribution->bloodRequest->hospital->hospital_name ?? '-' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Nama Kurir</label>
                            <div>{{ $bloodDistribution->courier_name }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Golongan Darah</label>
                                <div><span class="badge bg-danger fs-6">{{ $bloodDistribution->bloodRequest->blood_type ?? '-' }}{{ $bloodDistribution->bloodRequest->rhesus ?? '' }}</span></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Jumlah Kantong</label>
                                <div>{{ $bloodDistribution->bloodRequest->quantity ?? '-' }} Kantong</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Tanggal Distribusi</label>
                                <div>{{ \Carbon\Carbon::parse($bloodDistribution->distribution_date)->translatedFormat('d M Y') }}</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Ubah Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Diproses" {{ (old('status', $bloodDistribution->status) == 'Diproses') ? 'selected' : '' }}>Diproses</option>
                                <option value="Dikirim" {{ (old('status', $bloodDistribution->status) == 'Dikirim') ? 'selected' : '' }}>Dikirim</option>
                                <option value="Diterima" {{ (old('status', $bloodDistribution->status) == 'Diterima') ? 'selected' : '' }}>Diterima</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('blood-distributions.index') }}" class="btn btn-secondary rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
