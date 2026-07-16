@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Pengaturan Akun</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Pengaturan</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    <h5 class="card-title fw-bold mb-4 fs-5" style="color: #012970;">Ganti Password</h5>
                    
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <label for="current_password" class="col-md-4 col-lg-3 col-form-label fw-bold text-muted">Password Lama</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" placeholder="Masukkan password lama">
                                @error('current_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-lg-3 col-form-label fw-bold text-muted">Password Baru</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Masukkan password baru (Min. 8 karakter)">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label fw-bold text-muted">Konfirmasi Password Baru</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('profile') }}" class="btn btn-secondary rounded-pill px-4 me-2">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
