@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Notifikasi</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Notifikasi</li>
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
                            <form action="{{ route('notifications.index') }}" method="GET" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control rounded-pill" placeholder="Cari Judul..." value="{{ request('search') }}">
                                @if(request('search'))
                                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary rounded-pill d-flex align-items-center">
                                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="bi bi-search"></i></button>
                            </form>
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('notifications.create') }}" class="btn btn-danger text-nowrap rounded-pill px-4">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Notifikasi
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Pesan</th>
                                    <th scope="col">Target Role</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $key => $notification)
                                <tr>
                                    <th scope="row">{{ $notifications instanceof \Illuminate\Pagination\LengthAwarePaginator ? $notifications->firstItem() + $key : $loop->iteration }}</th>
                                    <td><span class="fw-bold text-primary">{{ $notification->title }}</span></td>
                                    <td>{{ Str::limit($notification->message, 50) }}</td>
                                    <td>
                                        @if($notification->target_role == 'all')
                                            Semua Role
                                        @elseif(in_array($notification->target_role, ['rs', 'rumah_sakit']))
                                            Rumah Sakit
                                        @else
                                            {{ ucfirst($notification->target_role) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification->status == 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($notification->created_at)->translatedFormat('d M Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-bell text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">
                                                Belum ada notifikasi.
                                            </h5>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator && $notifications->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Menampilkan {{ $notifications->firstItem() ?? 0 }}-{{ $notifications->lastItem() ?? 0 }} dari {{ $notifications->total() }} data.
                        </div>
                        <div>
                            {{ $notifications->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
