@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Buat Permintaan Darah</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('blood-requests.store') }}" method="POST">
                        @csrf
                        
                        @if(auth()->user()->hasRole('admin'))
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rumah Sakit</label>
                            <select name="hospital_id" class="form-select @error('hospital_id') is-invalid @enderror" required>
                                <option value="">Pilih Rumah Sakit</option>
                                @foreach($hospitals as $h)
                                    <option value="{{ $h->id }}">{{ $h->hospital_name }}</option>
                                @endforeach
                            </select>
                            @error('hospital_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Golongan Darah</label>
                            <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror" required>
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                            @error('blood_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rhesus</label>
                            <select name="rhesus" class="form-select @error('rhesus') is-invalid @enderror" required>
                                <option value="">Pilih Rhesus</option>
                                <option value="+">+</option>
                                <option value="-">-</option>
                            </select>
                            @error('rhesus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Kantong</label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('blood-requests.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-danger">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
