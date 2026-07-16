@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Manajemen Stok Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Manajemen Stok Darah</li>
        </ol>
    </nav>
</div>

<section class="section">
    <!-- Ringkasan Statistik -->
    <div class="row mb-3">
        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card sales-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Kantong Darah</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white" style="width: 50px; height: 50px; font-size: 24px;">
                            <i class="bi bi-droplet"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="fw-bold fs-3 mb-0">{{ number_format($totalKantong, 0, ',', '.') }}</h6>
                            <span class="text-muted small pt-1">Kantong Tersedia</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card revenue-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Volume Darah</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white" style="width: 50px; height: 50px; font-size: 24px;">
                            <i class="bi bi-water"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="fw-bold fs-3 mb-0">{{ number_format($totalVolume, 0, ',', '.') }}</h6>
                            <span class="text-muted small pt-1">Mililiter (ml)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card customers-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Golongan Darah</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 50px; height: 50px; font-size: 24px;">
                            <i class="bi bi-layers"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="fw-bold fs-3 mb-0">{{ $totalGolongan }}</h6>
                            <span class="text-muted small pt-1">Tipe Tersedia</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6 mb-4">
            <div class="card info-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Terakhir Diperbarui</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white" style="width: 50px; height: 50px; font-size: 24px;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="fw-bold fs-5 mb-0" style="color: #012970;">
                                {{ $terakhirDiperbarui ? \Carbon\Carbon::parse($terakhirDiperbarui)->translatedFormat('d M Y') : '-' }}
                            </h6>
                            <span class="text-muted small pt-1">
                                {{ $terakhirDiperbarui ? \Carbon\Carbon::parse($terakhirDiperbarui)->translatedFormat('H:i') . ' WIB' : 'Belum ada update' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel dan Filter -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-3 mb-2 mb-md-0">
                            <h5 class="fw-bold mb-0 text-muted">Daftar Stok Darah</h5>
                        </div>
                        <div class="col-md-9">
                            <form action="{{ route('blood-stocks.index') }}" method="GET" class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                                
                                <select name="blood_type" class="form-select rounded-pill w-auto" onchange="this.form.submit()">
                                    <option value="">Semua Golongan</option>
                                    <option value="A" {{ request('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ request('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ request('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ request('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                                </select>

                                <select name="rhesus" class="form-select rounded-pill w-auto" onchange="this.form.submit()">
                                    <option value="">Semua Rhesus</option>
                                    <option value="+" {{ request('rhesus') == '+' ? 'selected' : '' }}>Positif (+)</option>
                                    <option value="-" {{ request('rhesus') == '-' ? 'selected' : '' }}>Negatif (-)</option>
                                </select>

                                <div class="d-flex">
                                    <input type="text" name="search" class="form-control rounded-pill me-2" style="min-width: 200px;" placeholder="Cari..." value="{{ request('search') }}">
                                    @if(request('search') || request('blood_type') || request('rhesus'))
                                        <a href="{{ route('blood-stocks.index') }}" class="btn btn-outline-secondary rounded-pill me-2 d-flex align-items-center">
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
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Golongan Darah</th>
                                    <th scope="col" class="text-center">Rhesus</th>
                                    <th scope="col" class="text-center">Jumlah Kantong</th>
                                    <th scope="col" class="text-center">Total Volume (ml)</th>
                                    <th scope="col" class="text-center">Status Stok</th>
                                    <th scope="col" class="text-center">Terakhir Diperbarui</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bloodStocks as $key => $stock)
                                <tr>
                                    <th scope="row" class="text-center">{{ $bloodStocks->firstItem() + $key }}</th>
                                    <td class="text-center">
                                        @if($stock->blood_type == 'A')
                                            <span class="badge bg-danger px-3 py-2 fs-6">{{ $stock->blood_type }}</span>
                                        @elseif($stock->blood_type == 'B')
                                            <span class="badge bg-primary px-3 py-2 fs-6">{{ $stock->blood_type }}</span>
                                        @elseif($stock->blood_type == 'AB')
                                            <span class="badge px-3 py-2 fs-6" style="background-color: #6f42c1; color: white;">{{ $stock->blood_type }}</span>
                                        @elseif($stock->blood_type == 'O')
                                            <span class="badge bg-success px-3 py-2 fs-6">{{ $stock->blood_type }}</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 fs-6">{{ $stock->blood_type }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center fs-5 fw-bold">{{ $stock->rhesus }}</td>
                                    <td class="text-center fw-bold">{{ $stock->quantity_bag }}</td>
                                    <td class="text-center">{{ number_format($stock->total_volume_ml, 0, ',', '.') }} ml</td>
                                    <td class="text-center">
                                        @if($stock->quantity_bag == 0)
                                            <span class="badge bg-danger px-3 py-2">Habis</span>
                                        @elseif($stock->quantity_bag >= 1 && $stock->quantity_bag <= 5)
                                            <span class="badge bg-warning text-dark px-3 py-2">Menipis</span>
                                        @else
                                            <span class="badge bg-success px-3 py-2">Tersedia</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $stock->last_update ? \Carbon\Carbon::parse($stock->last_update)->translatedFormat('d M Y, H:i') : '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('blood-stocks.show', $stock->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-droplet text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">Belum ada stok darah tersedia.</h5>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan {{ $bloodStocks->firstItem() ?? 0 }}-{{ $bloodStocks->lastItem() ?? 0 }} dari {{ $bloodStocks->total() }} data.
                        </div>
                        <div>
                            {{ $bloodStocks->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
