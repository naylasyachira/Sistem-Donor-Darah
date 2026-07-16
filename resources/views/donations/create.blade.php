@extends('layouts.app')

@section('content')
<div class="pagetitle mb-4">
    <h1 class="fs-3 fw-bold" style="color: #012970;">Mulai Donor Darah</h1>
    <nav>
        <ol class="breadcrumb mt-1">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('donations.index') }}" class="text-decoration-none">Donor Darah</a></li>
            <li class="breadcrumb-item active">Mulai Donor</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body pt-4">
                    <form action="{{ route('donations.store') }}" method="POST" novalidate>
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="donation_code" class="form-label fw-bold text-muted">Kode Donor</label>
                                <input type="text" class="form-control bg-light" id="donation_code" name="donation_code" value="{{ $donationCode }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="donor_id" class="form-label fw-bold text-muted">Pendonor</label>
                                <select class="form-select @error('donor_id') is-invalid @enderror" id="donor_id" name="donor_id" required>
                                    <option value="" disabled selected>Pilih Pendonor</option>
                                    @foreach($donors as $donor)
                                        <option value="{{ $donor->id }}" 
                                            data-blood="{{ $donor->golongan_darah }}" 
                                            data-rhesus="{{ $donor->rhesus }}"
                                            data-screenings="{{ json_encode($donor->screenings) }}"
                                            {{ old('donor_id') == $donor->id ? 'selected' : '' }}>
                                            {{ $donor->donor_code }} - {{ $donor->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('donor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="screening_id" class="form-label fw-bold text-muted">Screening</label>
                                <select class="form-select @error('screening_id') is-invalid @enderror" id="screening_id" name="screening_id" required>
                                    <option value="" disabled selected>Pilih Screening</option>
                                </select>
                                @error('screening_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="donation_date" class="form-label fw-bold text-muted">Tanggal Donor</label>
                                <input type="date" class="form-control bg-light" id="donation_date" name="donation_date" value="{{ date('Y-m-d') }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="blood_type" class="form-label fw-bold text-muted">Golongan Darah</label>
                                <input type="text" class="form-control bg-light" id="blood_type" value="" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="rhesus" class="form-label fw-bold text-muted">Rhesus</label>
                                <input type="text" class="form-control bg-light" id="rhesus" value="" readonly>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="blood_volume" class="form-label fw-bold text-muted">Volume Darah (ml)</label>
                                <input type="number" class="form-control @error('blood_volume') is-invalid @enderror" id="blood_volume" name="blood_volume" value="{{ old('blood_volume', 350) }}" required>
                                @error('blood_volume')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-bold text-muted">Status Donor</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="" disabled selected>Pilih Status</option>
                                    <option value="Berhasil" {{ old('status') == 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                                    <option value="Dibatalkan" {{ old('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label fw-bold text-muted">Catatan Petugas</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('donations.index') }}" class="btn btn-secondary rounded-pill px-4 me-2">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const donorSelect = document.getElementById('donor_id');
    const screeningSelect = document.getElementById('screening_id');
    const bloodTypeInput = document.getElementById('blood_type');
    const rhesusInput = document.getElementById('rhesus');

    // Retrieve old screening_id if exists
    const oldScreeningId = "{{ old('screening_id') }}";

    function updateDonorFields() {
        const selectedOption = donorSelect.options[donorSelect.selectedIndex];
        
        // Reset screening dropdown
        screeningSelect.innerHTML = '<option value="" disabled selected>Pilih Screening</option>';
        bloodTypeInput.value = '';
        rhesusInput.value = '';

        if(selectedOption && selectedOption.value !== "") {
            const bloodType = selectedOption.getAttribute('data-blood');
            const rhesus = selectedOption.getAttribute('data-rhesus');
            const screeningsStr = selectedOption.getAttribute('data-screenings');
            
            if (bloodType) bloodTypeInput.value = bloodType;
            if (rhesus) rhesusInput.value = rhesus;
            
            if(screeningsStr) {
                try {
                    const screenings = JSON.parse(screeningsStr);
                    screenings.forEach(scr => {
                        const option = document.createElement('option');
                        option.value = scr.id;
                        // Format date to local if needed, here just raw
                        option.text = scr.screening_code + ' - ' + scr.screening_date;
                        
                        if(oldScreeningId == scr.id) {
                            option.selected = true;
                        }
                        
                        screeningSelect.appendChild(option);
                    });
                } catch (e) {
                    console.error("Error parsing screenings JSON", e);
                }
            }
        }
    }

    donorSelect.addEventListener('change', updateDonorFields);
    
    // Trigger on load for old() values
    if(donorSelect.value) {
        updateDonorFields();
    }
});
</script>
@endsection
