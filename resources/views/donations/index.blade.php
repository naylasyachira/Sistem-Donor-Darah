@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Daftar Donor Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Donor Darah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <a href="{{ route('donations.create') }}" class="btn btn-primary rounded-pill px-3">
                                <i class="bi bi-plus-circle me-1"></i> Mulai Donor
                            </a>
                        </div>
                        <div class="col-md-8">
                            <form action="{{ route('donations.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                                
                                <select name="status" class="form-select rounded-pill w-auto" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="Berhasil" {{ request('status') == 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                                    <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>

                                <div class="d-flex">
                                    <input type="text" name="search" class="form-control rounded-pill me-2" style="min-width: 250px;" placeholder="Cari kode, nama, gol darah, status..." value="{{ request('search') }}">
                                    @if(request('search') || request('status'))
                                        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary rounded-pill me-2 d-flex align-items-center">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="bi bi-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kode Donor</th>
                                    <th scope="col">Nama Pendonor</th>
                                    <th scope="col">Tanggal Donor</th>
                                    <th scope="col">Gol. Darah</th>
                                    <th scope="col">Rhesus</th>
                                    <th scope="col">Volume (ml)</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Petugas</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donations as $key => $donation)
                                <tr>
                                    <th scope="row">{{ $donations->firstItem() + $key }}</th>
                                    <td><span class="fw-bold text-primary">{{ $donation->donation_code }}</span></td>
                                    <td>{{ $donation->donor->nama_lengkap ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($donation->donation_date)->translatedFormat('d F Y') }}</td>
                                    <td class="text-center fw-bold text-danger">{{ $donation->blood_type }}</td>
                                    <td class="text-center">{{ $donation->rhesus }}</td>
                                    <td class="text-center">{{ $donation->blood_volume }}</td>
                                    <td>
                                        @if($donation->status == 'Berhasil')
                                            <span class="badge bg-success">Berhasil</span>
                                        @else
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>{{ $donation->user->name ?? '-' }}</td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('donations.show', $donation->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->hasRole(['admin', 'petugas']))
                                            <a href="{{ route('donations.edit', $donation->id) }}" class="btn btn-sm btn-warning rounded-pill mx-1" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasRole('admin'))
                                            <form action="{{ route('donations.destroy', $donation->id) }}" method="POST" class="d-inline">
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
                                            <i class="bi bi-droplet-half text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">Belum ada data donor.</h5>
                                            <a href="{{ route('donations.create') }}" class="btn btn-primary rounded-pill px-4 mt-3">
                                                <i class="bi bi-play-circle me-1"></i> Mulai Donor
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
                            Menampilkan {{ $donations->firstItem() ?? 0 }}-{{ $donations->lastItem() ?? 0 }} dari {{ $donations->total() }} data.
                        </div>
                        <div>
                            {{ $donations->withQueryString()->links('pagination::bootstrap-5') }}
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
                title: 'Hapus Data Donor?',
                text: "Data donor yang dihapus tidak dapat dikembalikan.",
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
