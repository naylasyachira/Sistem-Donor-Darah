@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">User Management</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">User Management</li>
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
                        @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-3">
                            <i class="bi bi-plus-circle me-1"></i> Tambah User
                        </a>
                        @else
                        <div></div>
                        @endif

                        <form action="{{ route('users.index') }}" method="GET" class="d-flex w-25">
                            <input type="text" name="search" class="form-control me-2 rounded-pill" placeholder="Cari nama atau email..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="bi bi-search"></i></button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $key => $u)
                                <tr>
                                    <th scope="row">{{ $users->firstItem() + $key }}</th>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=e0e8f9&color=012970&size=40" alt="Avatar" class="rounded-circle me-3">
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $u->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            if($u->role && $u->role->name === 'admin') $badgeClass = 'bg-danger';
                                            elseif($u->role && $u->role->name === 'petugas') $badgeClass = 'bg-primary';
                                            elseif($u->role && $u->role->name === 'rumah_sakit') $badgeClass = 'bg-success';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $u->role ? $u->role->display_name : 'No Role' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('users.show', $u->id) }}" class="btn btn-sm btn-info text-white rounded-pill" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-warning text-white rounded-pill" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline delete-form">
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
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-people text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                            <h5 class="mt-3 text-muted">Belum ada data user.</h5>
                                            @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4 mt-3">
                                                <i class="bi bi-plus-circle me-1"></i> Tambah User
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
                            Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data.
                        </div>
                        <div>
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus User?',
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
                })
            });
        });
        
        @if(session('success'))
        Swal.fire({
            title: 'MANTAP!',
            text: "{{ session('success') }}",
            icon: 'success',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
        @endif
    });
</script>
@endsection
