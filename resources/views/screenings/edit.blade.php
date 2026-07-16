@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Edit Screening Kesehatan</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('screenings.index') }}" class="text-decoration-none">Screening Kesehatan</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    <form action="{{ route('screenings.update', $screening->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="screening_code" class="form-label fw-bold text-muted">Kode Screening</label>
                                <input type="text" class="form-control bg-light" id="screening_code" name="screening_code" value="{{ $screening->screening_code }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="donor_id" class="form-label fw-bold text-muted">Pendonor</label>
                                <select class="form-select @error('donor_id') is-invalid @enderror" id="donor_id" name="donor_id" required>
                                    <option value="" disabled>Pilih Pendonor</option>
                                    @foreach($donors as $donor)
                                        <option value="{{ $donor->id }}" {{ old('donor_id', $screening->donor_id) == $donor->id ? 'selected' : '' }}>
                                            {{ $donor->donor_code }} - {{ $donor->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('donor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="screening_date" class="form-label fw-bold text-muted">Tanggal Screening</label>
                                <input type="date" class="form-control bg-light" id="screening_date" name="screening_date" value="{{ $screening->screening_date }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tekanan_darah" class="form-label fw-bold text-muted">Tekanan Darah (mmHg)</label>
                                <input type="text" class="form-control @error('tekanan_darah') is-invalid @enderror" id="tekanan_darah" name="tekanan_darah" value="{{ old('tekanan_darah', $screening->tekanan_darah) }}" placeholder="Contoh: 120/80" required>
                                @error('tekanan_darah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="berat_badan" class="form-label fw-bold text-muted">Berat Badan (Kg)</label>
                                <input type="number" step="0.1" class="form-control @error('berat_badan') is-invalid @enderror" id="berat_badan" name="berat_badan" value="{{ old('berat_badan', $screening->berat_badan) }}" required>
                                @error('berat_badan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tinggi_badan" class="form-label fw-bold text-muted">Tinggi Badan (cm)</label>
                                <input type="number" step="0.1" class="form-control @error('tinggi_badan') is-invalid @enderror" id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan', $screening->tinggi_badan) }}" required>
                                @error('tinggi_badan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hemoglobin" class="form-label fw-bold text-muted">Hemoglobin (g/dL)</label>
                                <input type="number" step="0.1" class="form-control @error('hemoglobin') is-invalid @enderror" id="hemoglobin" name="hemoglobin" value="{{ old('hemoglobin', $screening->hemoglobin) }}" required>
                                @error('hemoglobin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="suhu_tubuh" class="form-label fw-bold text-muted">Suhu Tubuh (°C)</label>
                                <input type="number" step="0.1" class="form-control @error('suhu_tubuh') is-invalid @enderror" id="suhu_tubuh" name="suhu_tubuh" value="{{ old('suhu_tubuh', $screening->suhu_tubuh) }}" required>
                                @error('suhu_tubuh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="denyut_nadi" class="form-label fw-bold text-muted">Denyut Nadi (bpm)</label>
                                <input type="number" class="form-control @error('denyut_nadi') is-invalid @enderror" id="denyut_nadi" name="denyut_nadi" value="{{ old('denyut_nadi', $screening->denyut_nadi) }}" required>
                                @error('denyut_nadi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold text-muted">Status Screening</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="" disabled>Pilih Status</option>
                                    <option value="Lulus" {{ old('status', $screening->status) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="Tidak Lulus" {{ old('status', $screening->status) == 'Tidak Lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label fw-bold text-muted">Catatan Petugas</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $screening->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('screenings.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
