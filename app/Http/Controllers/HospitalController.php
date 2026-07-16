<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');
        
        $search = $request->query('search');

        $hospitals = Hospital::when($search, function ($query, $search) {
                return $query->where('hospital_code', 'like', "%{$search}%")
                             ->orWhere('hospital_name', 'like', "%{$search}%")
                             ->orWhere('director_name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
            
        return view('hospitals.index', compact('hospitals', 'search'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');

        $lastHospital = Hospital::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastHospital && preg_match('/HSP(\d+)/', $lastHospital->hospital_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $hospitalCode = 'HSP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('hospitals.create', compact('hospitalCode'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');

        $rules = [
            'hospital_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hospitals,email',
            'phone' => 'required|string|max:20',
            'director_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif',
        ];

        $messages = [
            'hospital_name.required' => 'Nama Rumah Sakit wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor Telepon wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ];

        $request->validate($rules, $messages);

        $lastHospital = Hospital::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastHospital && preg_match('/HSP(\d+)/', $lastHospital->hospital_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $hospitalCode = 'HSP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Hospital::create([
            'hospital_code' => $hospitalCode,
            'hospital_name' => $request->hospital_name,
            'director_name' => $request->director_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('hospitals.index')->with('success', 'Data rumah sakit berhasil ditambahkan.');
    }

    public function show(Hospital $hospital)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');
        
        return view('hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');
        
        return view('hospitals.edit', compact('hospital'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');

        $rules = [
            'hospital_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hospitals,email,' . $hospital->id,
            'phone' => 'required|string|max:20',
            'director_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif',
        ];

        $messages = [
            'hospital_name.required' => 'Nama Rumah Sakit wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor Telepon wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ];

        $request->validate($rules, $messages);

        $hospital->update([
            'hospital_name' => $request->hospital_name,
            'director_name' => $request->director_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()->route('hospitals.index')->with('success', 'Data rumah sakit berhasil diperbarui.');
    }

    public function destroy(Hospital $hospital)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');
        
        $hospital->delete();
        
        return redirect()->route('hospitals.index')->with('success', 'Data rumah sakit berhasil dihapus.');
    }
}
