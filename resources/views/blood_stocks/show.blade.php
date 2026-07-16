@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Detail Stok Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blood-stocks.index') }}" class="text-decoration-none">Manajemen Stok Darah</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <!-- Informasi Stok -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body pt-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white 
                            @if($bloodStock->blood_type == 'A') bg-danger 
                            @elseif($bloodStock->blood_type == 'B') bg-primary 
                            @elseif($bloodStock->blood_type == 'AB') bg-secondary
                            @elseif($bloodStock->blood_type == 'O') bg-success 
                            @else bg-dark @endif" 
                            style="width: 70px; height: 70px; font-size: 30px; 
                            @if($bloodStock->blood_type == 'AB') background-color: #6f42c1 !important; @endif">
                            <span class="fw-bold">{{ $bloodStock->blood_type }}{{ $bloodStock->rhesus }}</span>
                        </div>
                        <div class="ms-3">
                            <h4 class="fw-bold mb-0">Golongan {{ $bloodStock->blood_type }}</h4>
                            <span class="text-muted">Rhesus {{ $bloodStock->rhesus == '+' ? 'Positif (+)' : 'Negatif (-)' }}</span>
                        </div>
                    </div>
                    
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Status Stok</span>
                            @if($bloodStock->quantity_bag == 0)
                                <span class="badge bg-danger px-3 py-2">Habis</span>
                            @elseif($bloodStock->quantity_bag >= 1 && $bloodStock->quantity_bag <= 5)
                                <span class="badge bg-warning text-dark px-3 py-2">Menipis</span>
                            @else
                                <span class="badge bg-success px-3 py-2">Tersedia</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Jumlah Kantong</span>
                            <span class="fw-bold fs-5">{{ $bloodStock->quantity_bag }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Total Volume</span>
                            <span class="fw-bold text-primary">{{ number_format($bloodStock->total_volume_ml, 0, ',', '.') }} ml</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Terakhir Diperbarui</span>
                            <span class="small fw-bold">{{ $bloodStock->last_update ? \Carbon\Carbon::parse($bloodStock->last_update)->translatedFormat('d M Y, H:i') : '-' }}</span>
                        </li>
                    </ul>

                    <div class="d-grid">
                        <a href="{{ route('blood-stocks.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Perubahan Stok -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body pt-4">
                    <h5 class="card-title fw-bold text-muted mb-4 pt-0">Riwayat Perubahan Stok (Dari Transaksi Donor)</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Kode Donor</th>
                                    <th scope="col">Nama Pendonor</th>
                                    <th scope="col" class="text-center">Volume Dasar</th>
                                    <th scope="col" class="text-end">Perubahan Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->donation_date)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        <a href="{{ route('donations.show', $item->id) }}" class="fw-bold text-decoration-none">
                                            {{ $item->donation_code }}
                                        </a>
                                    </td>
                                    <td>{{ $item->donor->nama_lengkap ?? '-' }}</td>
                                    <td class="text-center">{{ $item->blood_volume }} ml</td>
                                    <td class="text-end">
                                        <span class="badge bg-success px-3 py-2 fs-6">
                                            <i class="bi bi-plus-circle me-1"></i> +{{ $item->blood_volume }} ml
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-clock-history text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                            <p class="mt-3 text-muted mb-0">Belum ada riwayat transaksi yang terekam.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3 text-muted small fst-italic">
                        * Catatan: Data riwayat diambil secara otomatis dari transaksi Donor Darah berstatus "Berhasil". Stok darah tidak dapat diubah secara manual demi menjaga keutuhan data.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
