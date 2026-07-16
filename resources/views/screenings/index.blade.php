@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Screening Kesehatan</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Screening Kesehatan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('screenings.create') }}" class="btn btn-primary rounded-pill px-3">
                            <i class="bi bi-plus-circle me-1"></i> Mulai Screening
                        </a>

                        <form action="{{ route('screenings.index') }}" method="GET" class="d-flex" style="width: 35%;">
                            <input type="text" name="search" class="form-control me-2 rounded-pill" placeholder="Cari kode, nama pendonor, status..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('screenings.index') }}" class="btn btn-outline-secondary rounded-pill me-2 d-flex align-items-center">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                </a>
                            @endif
                            <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="bi bi-search"></i></button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kode Screening</th>
                                    <th scope="col">Nama Pendonor</th>
                                    <th scope="col">Tanggal Screening</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Petugas</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($screenings as $key => $screening)
                                <tr>
                                    <th scope="row">{{ $screenings->firstItem() + $key }}</th>
                                    <td><span class="fw-bold text-primary">{{ $screening->screening_code }}</span></td>
                                    <td>{{ $screening->donor->nama_lengkap ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($screening->screening_date)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if($screening->status == 'Lulus')
                                            <span class="badge bg-success">Lulus</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Lulus</span>
                                        @endif
                                    </td>
                                    <td>{{ $screening->user->name ?? '-' }}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('screenings.show', $screening->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('screenings.edit', $screening->id) }}" class="btn btn-sm btn-warning rounded-pill mx-1" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('screenings.destroy', $screening->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-heart-pulse text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">Belum ada data screening.</h5>
                                            <a href="{{ route('screenings.create') }}" class="btn btn-primary rounded-pill px-4 mt-3">
                                                <i class="bi bi-play-circle me-1"></i> Mulai Screening
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan {{ $screenings->firstItem() ?? 0 }}-{{ $screenings->lastItem() ?? 0 }} dari {{ $screenings->total() }} data.
                        </div>
                        <div>
                            {{ $screenings->withQueryString()->links('pagination::bootstrap-5') }}
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
                title: 'Hapus Screening?',
                text: "Data screening yang dihapus tidak dapat dikembalikan.",
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
