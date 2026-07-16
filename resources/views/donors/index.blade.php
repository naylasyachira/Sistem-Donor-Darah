@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Master Pendonor</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Master Pendonor</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('donors.create') }}" class="btn btn-primary rounded-pill px-3">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Pendonor
                        </a>

                        <form action="{{ route('donors.index') }}" method="GET" class="d-flex" style="width: 35%;">
                            <input type="text" name="search" class="form-control me-2 rounded-pill" placeholder="Cari kode, NIK, nama..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('donors.index') }}" class="btn btn-outline-secondary rounded-pill me-2 d-flex align-items-center">
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
                                    <th scope="col">Kode Pendonor</th>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Jenis Kelamin</th>
                                    <th scope="col">Golongan Darah</th>
                                    <th scope="col">Rhesus</th>
                                    <th scope="col">Nomor HP</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donors as $key => $donor)
                                <tr>
                                    <th scope="row">{{ $donors->firstItem() + $key }}</th>
                                    <td><span class="fw-bold text-primary">{{ $donor->donor_code }}</span></td>
                                    <td>{{ $donor->nik }}</td>
                                    <td>{{ $donor->nama_lengkap }}</td>
                                    <td>{{ $donor->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>
                                        @php
                                            $golDarahClass = 'bg-secondary';
                                            $golDarahStyle = '';
                                            if($donor->golongan_darah == 'A') $golDarahClass = 'bg-danger';
                                            elseif($donor->golongan_darah == 'B') $golDarahClass = 'bg-primary';
                                            elseif($donor->golongan_darah == 'AB') {
                                                $golDarahClass = '';
                                                $golDarahStyle = 'background-color: #6f42c1; color: white;';
                                            }
                                            elseif($donor->golongan_darah == 'O') $golDarahClass = 'bg-success';
                                        @endphp
                                        <span class="badge {{ $golDarahClass }}" style="{{ $golDarahStyle }}">{{ $donor->golongan_darah }}</span>
                                    </td>
                                    <td>{{ $donor->rhesus == '+' ? 'Positif (+)' : 'Negatif (-)' }}</td>
                                    <td>{{ $donor->no_hp }}</td>
                                    <td>
                                        @if($donor->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('donors.show', $donor->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('donors.edit', $donor->id) }}" class="btn btn-sm btn-warning rounded-pill mx-1" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('donors.destroy', $donor->id) }}" method="POST" class="d-inline">
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
                                    <td colspan="10" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-droplet text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">
                                                {{ request('search') ? 'Data pendonor tidak ditemukan.' : 'Belum ada data pendonor.' }}
                                            </h5>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan {{ $donors->firstItem() ?? 0 }}-{{ $donors->lastItem() ?? 0 }} dari {{ $donors->total() }} data.
                        </div>
                        <div>
                            {{ $donors->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Hapus Pendonor?',
                text: "Data pendonor yang dihapus tidak dapat dikembalikan.",
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
