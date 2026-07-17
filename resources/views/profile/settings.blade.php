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
            <!-- Edit Profile Form -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body pt-4">
                    <h5 class="card-title fw-bold mb-4 fs-5" style="color: #012970;">Edit Profil</h5>
                    
                    <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <label for="profile_photo" class="col-md-4 col-lg-3 col-form-label fw-bold text-muted">Foto Profil</label>
                            <div class="col-md-8 col-lg-9">
                                <div class="d-flex align-items-center mb-3">
                                    @if(auth()->user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" id="profilePreviewImage" class="rounded-circle me-3" width="80" height="80" style="object-fit: cover;">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=F8D7DA&color=DC3545" alt="Profile" id="profilePreviewImage" class="rounded-circle me-3" width="80" height="80">
                                    @endif
                                    <input name="profile_photo" type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" accept="image/*">
                                </div>
                                <div class="small text-muted">Format: JPG, PNG, GIF (Maks. 2MB)</div>
                                @error('profile_photo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-lg-3 col-form-label fw-bold text-muted">Nama Lengkap</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-lg-3 col-form-label fw-bold text-muted">Email</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Profil</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
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
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Ubah Password</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const photoInput = document.getElementById('profile_photo');
        const previewImage = document.getElementById('profilePreviewImage');
        
        if (photoInput && previewImage) {
            const originalSrc = previewImage.src;
            
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                if (file) {
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'File yang dipilih bukan gambar.',
                            showConfirmButton: true,
                            backdrop: 'rgba(0,0,0,0.4)'
                        });
                        photoInput.value = ''; // Reset file input
                        previewImage.src = originalSrc; // Reset image preview
                        return;
                    }
                    // Show preview
                    previewImage.src = URL.createObjectURL(file);
                } else {
                    // Revert to original if cancelled
                    previewImage.src = originalSrc;
                }
            });
        }
    });
</script>
@endsection
