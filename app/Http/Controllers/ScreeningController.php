<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Models\Donor;
use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');
        
        $search = $request->query('search');

        $screenings = Screening::with(['donor', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('screening_code', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhereHas('donor', function ($q2) use ($search) {
                          $q2->where('nama_lengkap', 'like', "%{$search}%");
                      });
                });
            })->latest()->paginate(10);
            
        return view('screenings.index', compact('screenings', 'search'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');

        $donors = Donor::all();
        
        $lastScreening = Screening::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastScreening && preg_match('/SCR(\d+)/', $lastScreening->screening_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $screeningCode = 'SCR' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('screenings.create', compact('donors', 'screeningCode'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');

        $rules = [
            'donor_id' => 'required|exists:donors,id',
            'tekanan_darah' => 'required|string|max:10',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'hemoglobin' => 'required|numeric',
            'suhu_tubuh' => 'required|numeric',
            'denyut_nadi' => 'required|numeric',
            'status' => 'required|in:Lulus,Tidak Lulus',
            'notes' => 'nullable|string',
        ];

        $messages = [
            'donor_id.required' => 'Pendonor wajib dipilih.',
            'donor_id.exists' => 'Pendonor tidak ditemukan.',
            'tekanan_darah.required' => 'Tekanan darah wajib diisi.',
            'berat_badan.required' => 'Berat badan wajib diisi.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
            'hemoglobin.required' => 'Hemoglobin wajib diisi.',
            'hemoglobin.numeric' => 'Hemoglobin harus berupa angka.',
            'suhu_tubuh.required' => 'Suhu tubuh wajib diisi.',
            'suhu_tubuh.numeric' => 'Suhu tubuh harus berupa angka.',
            'denyut_nadi.required' => 'Denyut nadi wajib diisi.',
            'denyut_nadi.numeric' => 'Denyut nadi harus berupa angka.',
            'status.required' => 'Status screening wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ];

        $request->validate($rules, $messages);

        $lastScreening = Screening::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastScreening && preg_match('/SCR(\d+)/', $lastScreening->screening_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }
        $screeningCode = 'SCR' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Screening::create([
            'donor_id' => $request->donor_id,
            'user_id' => auth()->id(),
            'screening_code' => $screeningCode,
            'screening_date' => date('Y-m-d'),
            'tekanan_darah' => $request->tekanan_darah,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'hemoglobin' => $request->hemoglobin,
            'suhu_tubuh' => $request->suhu_tubuh,
            'denyut_nadi' => $request->denyut_nadi,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('screenings.index')->with('success', 'Data screening berhasil ditambahkan.');
    }

    public function show(Screening $screening)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas']), 403, 'Anda tidak memiliki hak akses ke modul ini.');
        
        return view('screenings.show', compact('screening'));
    }

    public function edit(Screening $screening)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');
        
        $donors = Donor::all();
        return view('screenings.edit', compact('screening', 'donors'));
    }

    public function update(Request $request, Screening $screening)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');

        $rules = [
            'donor_id' => 'required|exists:donors,id',
            'tekanan_darah' => 'required|string|max:10',
            'berat_badan' => 'required|numeric',
            'tinggi_badan' => 'required|numeric',
            'hemoglobin' => 'required|numeric',
            'suhu_tubuh' => 'required|numeric',
            'denyut_nadi' => 'required|numeric',
            'status' => 'required|in:Lulus,Tidak Lulus',
            'notes' => 'nullable|string',
        ];

        $messages = [
            'donor_id.required' => 'Pendonor wajib dipilih.',
            'donor_id.exists' => 'Pendonor tidak ditemukan.',
            'tekanan_darah.required' => 'Tekanan darah wajib diisi.',
            'berat_badan.required' => 'Berat badan wajib diisi.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
            'hemoglobin.required' => 'Hemoglobin wajib diisi.',
            'hemoglobin.numeric' => 'Hemoglobin harus berupa angka.',
            'suhu_tubuh.required' => 'Suhu tubuh wajib diisi.',
            'suhu_tubuh.numeric' => 'Suhu tubuh harus berupa angka.',
            'denyut_nadi.required' => 'Denyut nadi wajib diisi.',
            'denyut_nadi.numeric' => 'Denyut nadi harus berupa angka.',
            'status.required' => 'Status screening wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ];

        $request->validate($rules, $messages);

        $screening->update([
            'donor_id' => $request->donor_id,
            'tekanan_darah' => $request->tekanan_darah,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'hemoglobin' => $request->hemoglobin,
            'suhu_tubuh' => $request->suhu_tubuh,
            'denyut_nadi' => $request->denyut_nadi,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('screenings.index')->with('success', 'Data screening berhasil diubah.');
    }

    public function destroy(Screening $screening)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403, 'Anda tidak memiliki hak akses ke fitur ini.');
        
        $screening->delete();
        
        return redirect()->route('screenings.index')->with('success', 'Data screening berhasil dihapus.');
    }
}
