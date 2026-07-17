@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Ubah Status Permintaan Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blood-requests.index') }}" class="text-decoration-none">Permintaan Darah</a></li>
            <li class="breadcrumb-item active">Ubah Status</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('blood-requests.update', $bloodRequest->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Kode Request</label>
                            <div class="fs-5 fw-bold text-primary">{{ $bloodRequest->request_code }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Rumah Sakit</label>
                            <div>{{ $bloodRequest->hospital->hospital_name ?? '-' }}</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Golongan Darah</label>
                                <div><span class="badge bg-danger fs-6">{{ $bloodRequest->blood_type }}</span></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Rhesus</label>
                                <div>{{ $bloodRequest->rhesus == '+' ? 'Positif (+)' : 'Negatif (-)' }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted">Jumlah Kantong</label>
                                <div>{{ $bloodRequest->quantity }}</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Ubah Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Menunggu" {{ (old('status', $bloodRequest->status) == 'Menunggu') ? 'selected' : '' }}>Menunggu</option>
                                <option value="Diproses" {{ (old('status', $bloodRequest->status) == 'Diproses') ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ (old('status', $bloodRequest->status) == 'Selesai') ? 'selected' : '' }}>Selesai</option>
                                <option value="Ditolak" {{ (old('status', $bloodRequest->status) == 'Ditolak') ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('blood-requests.index') }}" class="btn btn-secondary rounded-pill px-4">Batal</a>
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
