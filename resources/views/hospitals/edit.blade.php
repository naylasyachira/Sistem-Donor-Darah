@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Edit Rumah Sakit</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hospitals.index') }}" class="text-decoration-none">Data Rumah Sakit</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    <form action="{{ route('hospitals.update', $hospital->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hospital_code" class="form-label fw-bold text-muted">Kode Rumah Sakit</label>
                                <input type="text" class="form-control bg-light" id="hospital_code" name="hospital_code" value="{{ $hospital->hospital_code }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="hospital_name" class="form-label fw-bold text-muted">Nama Rumah Sakit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('hospital_name') is-invalid @enderror" id="hospital_name" name="hospital_name" value="{{ old('hospital_name', $hospital->hospital_name) }}" required>
                                @error('hospital_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="director_name" class="form-label fw-bold text-muted">Nama Direktur</label>
                                <input type="text" class="form-control @error('director_name') is-invalid @enderror" id="director_name" name="director_name" value="{{ old('director_name', $hospital->director_name) }}">
                                @error('director_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold text-muted">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="" disabled>Pilih Status</option>
                                    <option value="Aktif" {{ old('status', $hospital->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status', $hospital->status) == 'Nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold text-muted">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $hospital->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold text-muted">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $hospital->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="address" class="form-label fw-bold text-muted">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $hospital->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end border-top pt-4">
                            <a href="{{ route('hospitals.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
