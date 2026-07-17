@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Tambah Distribusi Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blood-distributions.index') }}" class="text-decoration-none">Distribusi Darah</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('blood-distributions.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="blood_request_id" class="form-label fw-bold">Permintaan Darah (Status: Diproses) <span class="text-danger">*</span></label>
                            <select class="form-select @error('blood_request_id') is-invalid @enderror" id="blood_request_id" name="blood_request_id" required>
                                <option value="" disabled selected>-- Pilih Permintaan Darah --</option>
                                @foreach($bloodRequests as $request)
                                    <option value="{{ $request->id }}" {{ old('blood_request_id') == $request->id ? 'selected' : '' }}>
                                        {{ $request->request_code }} - {{ $request->hospital->hospital_name ?? '-' }} (Gol. Darah: {{ $request->blood_type }}{{ $request->rhesus }}, Jml: {{ $request->quantity }})
                                    </option>
                                @endforeach
                            </select>
                            @error('blood_request_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($bloodRequests->isEmpty())
                                <small class="text-danger">Tidak ada permintaan darah dengan status "Diproses".</small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="courier_name" class="form-label fw-bold">Nama Kurir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('courier_name') is-invalid @enderror" id="courier_name" name="courier_name" value="{{ old('courier_name') }}" required placeholder="Masukkan nama kurir">
                            @error('courier_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="distribution_date" class="form-label fw-bold">Tanggal Distribusi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('distribution_date') is-invalid @enderror" id="distribution_date" name="distribution_date" value="{{ old('distribution_date', date('Y-m-d')) }}" required>
                            @error('distribution_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Status Distribusi</label>
                            <input type="text" class="form-control" id="status" value="Diproses" readonly disabled>
                            <small class="text-muted">Status awal distribusi otomatis diset ke "Diproses".</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('blood-distributions.index') }}" class="btn btn-secondary rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4" {{ $bloodRequests->isEmpty() ? 'disabled' : '' }}>
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
