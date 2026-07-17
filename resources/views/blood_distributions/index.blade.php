@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Distribusi Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Distribusi Darah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div></div>
                        
                        <div class="d-flex gap-2 align-items-center">
                            <form action="{{ route('blood-distributions.index') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control rounded-pill" placeholder="Cari Kode atau RS..." value="{{ request('search') }}">
                                @if(request('search'))
                                    <a href="{{ route('blood-distributions.index') }}" class="btn btn-outline-secondary rounded-pill d-flex align-items-center">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="bi bi-search"></i></button>
                            </form>

                                @if(auth()->user()->hasRole(['admin', 'petugas']))
                                <a href="{{ route('blood-distributions.create') }}" class="btn btn-danger text-nowrap">
                                    + Tambah Distribusi
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Kode Distribusi</th>
                                        <th scope="col">Kode Permintaan</th>
                                        <th scope="col">Rumah Sakit</th>
                                        <th scope="col">Golongan Darah</th>
                                        <th scope="col">Jumlah Kantong</th>
                                        <th scope="col">Tanggal Distribusi</th>
                                        <th scope="col">Status Distribusi</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($distributions as $key => $distribution)
                                    <tr>
                                        <th scope="row">{{ $distributions instanceof \Illuminate\Pagination\LengthAwarePaginator ? $distributions->firstItem() + $key : $loop->iteration }}</th>
                                        <td><span class="fw-bold text-primary">{{ $distribution->distribution_code }}</span></td>
                                        <td>{{ $distribution->bloodRequest->request_code ?? '-' }}</td>
                                        <td>{{ $distribution->bloodRequest->hospital->hospital_name ?? '-' }}</td>
                                        <td>
                                            @php
                                                $bloodType = $distribution->bloodRequest->blood_type ?? '-';
                                                $golDarahClass = 'bg-secondary';
                                                $golDarahStyle = '';
                                                if($bloodType == 'A') $golDarahClass = 'bg-danger';
                                                elseif($bloodType == 'B') $golDarahClass = 'bg-primary';
                                                elseif($bloodType == 'AB') {
                                                    $golDarahClass = '';
                                                    $golDarahStyle = 'background-color: #6f42c1; color: white;';
                                                }
                                                elseif($bloodType == 'O') $golDarahClass = 'bg-success';
                                            @endphp
                                            @if($bloodType != '-')
                                                <span class="badge {{ $golDarahClass }}" style="{{ $golDarahStyle }}">{{ $bloodType }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $distribution->bloodRequest->quantity ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($distribution->distribution_date)->translatedFormat('d M Y') }}</td>
                                        <td>
                                            @if($distribution->status == 'Diproses')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Diproses</span>
                                            @elseif($distribution->status == 'Dikirim')
                                                <span class="badge bg-info"><i class="bi bi-truck me-1"></i>Dikirim</span>
                                            @elseif($distribution->status == 'Diterima')
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Diterima</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="{{ route('blood-distributions.show', $distribution->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(auth()->user()->hasRole(['admin', 'petugas']))
                                            <a href="{{ route('blood-distributions.edit', $distribution->id) }}" class="btn btn-sm btn-warning rounded-pill mx-1" title="Ubah Status">
                                                <i class="bi bi-pencil"></i> Ubah Status
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-truck text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">
                                                Belum ada data distribusi darah.
                                            </h5>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($distributions instanceof \Illuminate\Pagination\LengthAwarePaginator && $distributions->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan {{ $distributions->firstItem() ?? 0 }}-{{ $distributions->lastItem() ?? 0 }} dari {{ $distributions->total() }} data.
                        </div>
                        <div>
                            {{ $distributions->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
