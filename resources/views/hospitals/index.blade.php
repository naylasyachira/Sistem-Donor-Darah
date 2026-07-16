@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Data Rumah Sakit</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Rumah Sakit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('hospitals.create') }}" class="btn btn-primary rounded-pill px-3">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Rumah Sakit
                            </a>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('hospitals.index') }}" method="GET" class="d-flex justify-content-md-end">
                                <input type="text" name="search" class="form-control rounded-pill me-2" style="max-width: 300px;" placeholder="Cari kode, nama, direktur..." value="{{ request('search') }}">
                                @if(request('search'))
                                    <a href="{{ route('hospitals.index') }}" class="btn btn-outline-secondary rounded-pill me-2 d-flex align-items-center">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col">Kode RS</th>
                                    <th scope="col">Nama Rumah Sakit</th>
                                    <th scope="col">Direktur</th>
                                    <th scope="col">Telepon</th>
                                    <th scope="col">Email</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hospitals as $key => $hospital)
                                <tr>
                                    <th scope="row" class="text-center">{{ $hospitals->firstItem() + $key }}</th>
                                    <td><span class="fw-bold text-primary">{{ $hospital->hospital_code }}</span></td>
                                    <td class="fw-bold">{{ $hospital->hospital_name }}</td>
                                    <td>{{ $hospital->director_name ?? '-' }}</td>
                                    <td>{{ $hospital->phone ?? '-' }}</td>
                                    <td>{{ $hospital->email ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($hospital->status == 'Aktif')
                                            <span class="badge bg-success px-3 py-2">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('hospitals.show', $hospital->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('hospitals.edit', $hospital->id) }}" class="btn btn-sm btn-warning rounded-pill mx-1" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('hospitals.destroy', $hospital->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger rounded-pill btn-delete" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-hospital text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">Belum ada data rumah sakit.</h5>
                                            @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('hospitals.create') }}" class="btn btn-primary rounded-pill px-4 mt-3">
                                                <i class="bi bi-plus-circle me-1"></i> Tambah Rumah Sakit
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan {{ $hospitals->firstItem() ?? 0 }}-{{ $hospitals->lastItem() ?? 0 }} dari {{ $hospitals->total() }} data.
                        </div>
                        <div>
                            {{ $hospitals->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#012970'
        });
    @endif

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Hapus Data Rumah Sakit?',
                text: "Data yang dihapus tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
