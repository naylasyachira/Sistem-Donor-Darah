@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Tambah Notifikasi</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('notifications.index') }}" class="text-decoration-none">Notifikasi</a></li>
            <li class="breadcrumb-item active">Tambah Notifikasi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Form Tambah Notifikasi</h5>

                    <form action="{{ route('notifications.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required maxlength="100" placeholder="Masukkan judul notifikasi">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">Pesan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" required placeholder="Masukkan isi pesan notifikasi">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="target_role" class="form-label fw-bold">Target Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('target_role') is-invalid @enderror" id="target_role" name="target_role" required>
                                <option value="" disabled selected>Pilih Target Role</option>
                                <option value="all" {{ old('target_role') == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="petugas" {{ old('target_role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                <option value="rs" {{ old('target_role') == 'rs' || old('target_role') == 'rumah_sakit' ? 'selected' : '' }}>Rumah Sakit</option>
                            </select>
                            @error('target_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="" disabled>Pilih Status</option>
                                <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('notifications.index') }}" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Notifikasi</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
