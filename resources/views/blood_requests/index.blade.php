@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Permintaan Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Permintaan Darah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            
            @if(isset($needsProfile) && $needsProfile)
            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                <div>
                    <strong>Data Rumah Sakit Belum Lengkap!</strong>
                    <p class="mb-2">Anda perlu melengkapi profil rumah sakit sebelum dapat membuat permintaan darah.</p>
                    <a href="{{ route('profile') }}" class="btn btn-warning rounded-pill">Lengkapi Profil Rumah Sakit</a>
                </div>
            </div>
            @else

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div></div>
                        
                        <div class="d-flex gap-2 align-items-center">
                            <form action="{{ route('blood-requests.index') }}" method="GET" class="d-flex gap-2">
                                @if(auth()->user()->hasRole(['admin', 'petugas']))
                                <input type="text" name="search" class="form-control rounded-pill" placeholder="Cari Rumah Sakit..." value="{{ request('search') }}">
                                @endif
                                <select name="status" class="form-select rounded-pill">
                                    <option value="">Semua Status</option>
                                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @if(request('search') || request('status'))
                                    <a href="{{ route('blood-requests.index') }}" class="btn btn-outline-secondary rounded-pill d-flex align-items-center">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="bi bi-search"></i></button>
                            </form>
                            
                            @if(auth()->user()->hasRole(['rs', 'rumah_sakit']))
                            <a href="{{ route('blood-requests.create') }}" class="btn btn-danger text-nowrap">
                                + Buat Permintaan Darah
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kode Request</th>
                                    @if(auth()->user()->hasRole(['admin', 'petugas']))
                                    <th scope="col">Rumah Sakit</th>
                                    @endif
                                    <th scope="col">Golongan Darah</th>
                                    <th scope="col">Rhesus</th>
                                    <th scope="col">Jumlah (Kantong)</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bloodRequests as $key => $request)
                                <tr>
                                    <th scope="row">{{ $bloodRequests instanceof \Illuminate\Pagination\LengthAwarePaginator ? $bloodRequests->firstItem() + $key : $loop->iteration }}</th>
                                    <td><span class="fw-bold text-primary">{{ $request->request_code }}</span></td>
                                    @if(auth()->user()->hasRole(['admin', 'petugas']))
                                    <td>{{ $request->hospital->hospital_name ?? '-' }}</td>
                                    @endif
                                    <td>
                                        @php
                                            $golDarahClass = 'bg-secondary';
                                            $golDarahStyle = '';
                                            if($request->blood_type == 'A') $golDarahClass = 'bg-danger';
                                            elseif($request->blood_type == 'B') $golDarahClass = 'bg-primary';
                                            elseif($request->blood_type == 'AB') {
                                                $golDarahClass = '';
                                                $golDarahStyle = 'background-color: #6f42c1; color: white;';
                                            }
                                            elseif($request->blood_type == 'O') $golDarahClass = 'bg-success';
                                        @endphp
                                        <span class="badge {{ $golDarahClass }}" style="{{ $golDarahStyle }}">{{ $request->blood_type }}</span>
                                    </td>
                                    <td>{{ $request->rhesus == '+' ? 'Positif (+)' : 'Negatif (-)' }}</td>
                                    <td>{{ $request->quantity }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->request_date)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @if($request->status == 'Menunggu')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                                        @elseif($request->status == 'Diproses')
                                            <span class="badge bg-info"><i class="bi bi-arrow-repeat me-1"></i>Diproses</span>
                                        @elseif($request->status == 'Selesai')
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                                        @elseif($request->status == 'Ditolak')
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('blood-requests.show', $request->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->hasRole(['admin', 'petugas']))
                                            <a href="{{ route('blood-requests.edit', $request->id) }}" class="btn btn-sm btn-warning rounded-pill mx-1" title="Ubah Status">
                                                <i class="bi bi-pencil"></i> Ubah Status
                                            </a>
                                            @if(auth()->user()->hasRole('admin'))
                                            <form action="{{ route('blood-requests.destroy', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger rounded-pill btn-delete" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-medical text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">
                                                {{ (request('search') || request('status')) ? 'Data permintaan darah tidak ditemukan.' : 'Belum ada data permintaan darah.' }}
                                            </h5>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($bloodRequests instanceof \Illuminate\Pagination\LengthAwarePaginator && $bloodRequests->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan {{ $bloodRequests->firstItem() ?? 0 }}-{{ $bloodRequests->lastItem() ?? 0 }} dari {{ $bloodRequests->total() }} data.
                        </div>
                        <div>
                            {{ $bloodRequests->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            
            @endif
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
                title: 'Hapus Permintaan Darah?',
                text: "Data permintaan yang dihapus tidak dapat dikembalikan.",
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
