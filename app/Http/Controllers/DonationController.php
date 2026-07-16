<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Donor;
use App\Models\Screening;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');
        
        $search = $request->query('search');
        $status = $request->query('status');

        $donations = Donation::with(['donor', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('donation_code', 'like', "%{$search}%")
                      ->orWhere('blood_type', 'like', "%{$search}%")
                      ->orWhere('rhesus', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhereHas('donor', function ($q2) use ($search) {
                          $q2->where('nama_lengkap', 'like', "%{$search}%");
                      });
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);
            
        return view('donations.index', compact('donations', 'search', 'status'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');

        // Hanya ambil pendonor yang memiliki screening "Lulus" dan screening tersebut belum dipakai
        $donors = Donor::whereHas('screenings', function($q) {
            $q->where('status', 'Lulus')->doesntHave('donations');
        })->with(['screenings' => function($q) {
            $q->where('status', 'Lulus')->doesntHave('donations');
        }])->get();

        $lastDonation = Donation::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastDonation && preg_match('/DON(\d+)/', $lastDonation->donation_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $donationCode = 'DON' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('donations.create', compact('donors', 'donationCode'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');

        $rules = [
            'donor_id' => 'required|exists:donors,id',
            'screening_id' => 'required|exists:screenings,id|unique:donations,screening_id',
            'blood_volume' => 'required|numeric|min:250|max:500',
            'status' => 'required|in:Berhasil,Dibatalkan',
            'notes' => 'nullable|string',
        ];

        $messages = [
            'donor_id.required' => 'Pendonor wajib dipilih.',
            'donor_id.exists' => 'Pendonor tidak valid.',
            'screening_id.required' => 'Screening wajib dipilih.',
            'screening_id.exists' => 'Screening tidak valid.',
            'screening_id.unique' => 'Screening ini sudah digunakan untuk donor lain.',
            'blood_volume.required' => 'Volume darah wajib diisi.',
            'blood_volume.numeric' => 'Volume darah harus berupa angka.',
            'blood_volume.min' => 'Volume darah minimal 250 ml.',
            'blood_volume.max' => 'Volume darah maksimal 500 ml.',
            'status.required' => 'Status donor wajib dipilih.',
            'status.in' => 'Status donor tidak valid.',
        ];

        $request->validate($rules, $messages);

        $lastDonation = Donation::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastDonation && preg_match('/DON(\d+)/', $lastDonation->donation_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $donationCode = 'DON' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $donor = Donor::find($request->donor_id);

        Donation::create([
            'donation_code' => $donationCode,
            'donor_id' => $request->donor_id,
            'screening_id' => $request->screening_id,
            'user_id' => auth()->id(),
            'donation_date' => date('Y-m-d'),
            'blood_type' => $donor->golongan_darah,
            'rhesus' => $donor->rhesus,
            'blood_volume' => $request->blood_volume,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('donations.index')->with('success', 'Data donor berhasil disimpan.');
    }

    public function show(Donation $donation)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');
        
        return view('donations.show', compact('donation'));
    }

    public function edit(Donation $donation)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke fitur ini.');
        
        $donors = Donor::whereHas('screenings', function($q) use ($donation) {
            $q->where('status', 'Lulus')->where(function ($q2) use ($donation) {
                $q2->doesntHave('donations')->orWhere('id', $donation->screening_id);
            });
        })->with(['screenings' => function($q) use ($donation) {
            $q->where('status', 'Lulus')->where(function ($q2) use ($donation) {
                $q2->doesntHave('donations')->orWhere('id', $donation->screening_id);
            });
        }])->get();

        return view('donations.edit', compact('donation', 'donors'));
    }

    public function update(Request $request, Donation $donation)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke fitur ini.');

        $rules = [
            'donor_id' => 'required|exists:donors,id',
            'screening_id' => 'required|exists:screenings,id|unique:donations,screening_id,' . $donation->id,
            'blood_volume' => 'required|numeric|min:250|max:500',
            'status' => 'required|in:Berhasil,Dibatalkan',
            'notes' => 'nullable|string',
        ];

        $messages = [
            'donor_id.required' => 'Pendonor wajib dipilih.',
            'donor_id.exists' => 'Pendonor tidak valid.',
            'screening_id.required' => 'Screening wajib dipilih.',
            'screening_id.exists' => 'Screening tidak valid.',
            'screening_id.unique' => 'Screening ini sudah digunakan untuk donor lain.',
            'blood_volume.required' => 'Volume darah wajib diisi.',
            'blood_volume.numeric' => 'Volume darah harus berupa angka.',
            'blood_volume.min' => 'Volume darah minimal 250 ml.',
            'blood_volume.max' => 'Volume darah maksimal 500 ml.',
            'status.required' => 'Status donor wajib dipilih.',
            'status.in' => 'Status donor tidak valid.',
        ];

        $request->validate($rules, $messages);

        $donor = Donor::find($request->donor_id);

        $donation->update([
            'donor_id' => $request->donor_id,
            'screening_id' => $request->screening_id,
            'blood_type' => $donor->golongan_darah,
            'rhesus' => $donor->rhesus,
            'blood_volume' => $request->blood_volume,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('donations.index')->with('success', 'Data donor berhasil diubah.');
    }

    public function destroy(Donation $donation)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');
        
        $donation->delete();
        
        return redirect()->route('donations.index')->with('success', 'Data donor berhasil dihapus.');
    }
}
