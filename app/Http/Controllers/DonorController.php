<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $donors = Donor::when($search, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('donor_code', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('golongan_darah', 'like', "%{$search}%")
                  ->orWhere('rhesus', 'like', "%{$search}%");
            });
        })->latest()->paginate(10);

        return view('donors.index', compact('donors', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses untuk menambah data.');

        $lastDonor = Donor::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastDonor && preg_match('/DNR(\d+)/', $lastDonor->donor_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $donorCode = 'DNR' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        return view('donors.create', compact('donorCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses untuk memproses data.');

        $rules = [
            'nik' => 'required|numeric|digits:16|unique:donors,nik',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'golongan_darah' => 'required|in:A,B,AB,O',
            'rhesus' => 'required|in:+,-',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'alamat' => 'nullable|string',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ];

        $messages = [
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus berjumlah 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            
            'golongan_darah.required' => 'Golongan darah wajib dipilih.',
            'golongan_darah.in' => 'Pilihan golongan darah tidak valid.',
            
            'rhesus.required' => 'Rhesus wajib dipilih.',
            'rhesus.in' => 'Pilihan rhesus tidak valid.',
            
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            
            'no_hp.required' => 'Nomor HP wajib diisi.',
            
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ];

        $request->validate($rules, $messages);

        $lastDonor = Donor::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastDonor && preg_match('/DNR(\d+)/', $lastDonor->donor_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $donorCode = 'DNR' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Donor::create([
            'donor_code' => $donorCode,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'golongan_darah' => $request->golongan_darah,
            'rhesus' => $request->rhesus,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ]);

        return redirect()->route('donors.index')->with('success', 'Data pendonor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donor $donor)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses.');
        
        return view('donors.show', compact('donor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donor $donor)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses untuk mengedit data.');
        
        return view('donors.edit', compact('donor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donor $donor)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses untuk memproses data.');

        $rules = [
            'nik' => 'required|numeric|digits:16|unique:donors,nik,' . $donor->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'golongan_darah' => 'required|in:A,B,AB,O',
            'rhesus' => 'required|in:+,-',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'alamat' => 'nullable|string',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:aktif,nonaktif',
        ];

        $messages = [
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus berjumlah 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            
            'golongan_darah.required' => 'Golongan darah wajib dipilih.',
            'golongan_darah.in' => 'Pilihan golongan darah tidak valid.',
            
            'rhesus.required' => 'Rhesus wajib dipilih.',
            'rhesus.in' => 'Pilihan rhesus tidak valid.',
            
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            
            'no_hp.required' => 'Nomor HP wajib diisi.',
            
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ];

        $request->validate($rules, $messages);

        $donor->update([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'golongan_darah' => $request->golongan_darah,
            'rhesus' => $request->rhesus,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ]);

        return redirect()->route('donors.index')->with('success', 'Data pendonor berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donor $donor)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses untuk menghapus data.');
        
        $donor->delete();
        
        return redirect()->route('donors.index')->with('success', 'Data pendonor berhasil dihapus.');
    }
}
