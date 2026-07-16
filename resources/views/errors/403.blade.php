@extends('layouts.app')

@section('content')
<div class="container">
    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1 style="color: #012970; font-size: 100px; font-weight: 700;">403</h1>
        <h2 style="font-size: 24px; color: #444444; margin-bottom: 30px;">403 Forbidden</h2>
        <p class="text-center" style="color: #444444; margin-bottom: 30px;">Anda tidak memiliki hak akses untuk membuka halaman ini.</p>
        <a class="btn btn-primary rounded-pill px-4 py-2" href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
    </section>
</div>
@endsection
