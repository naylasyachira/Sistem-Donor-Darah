<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Hospital;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    private function getHospitalForUser($user)
    {
        $hospital = Hospital::where('user_id', $user->id)->first();
        if (!$hospital) {
            $hospital = Hospital::whereNull('user_id')->first();
            if ($hospital) {
                $hospital->user_id = $user->id;
                $hospital->save();
            }
        }
        return $hospital;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $needsProfile = false;
        
        if ($user->hasRole(['admin', 'petugas'])) {
            $query = BloodRequest::with('hospital');
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('hospital', function($q) use ($search) {
                    $q->where('hospital_name', 'like', "%{$search}%");
                });
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $bloodRequests = $query->latest()->paginate(10);
        } elseif ($user->hasRole(['rs', 'rumah_sakit'])) {
            $hospital = $this->getHospitalForUser($user);
            if (!$hospital) {
                $needsProfile = true;
                $bloodRequests = collect(); 
            } else {
                $query = BloodRequest::where('hospital_id', $hospital->id);
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                $bloodRequests = $query->latest()->paginate(10);
            }
        } else {
            abort(403);
        }

        return view('blood_requests.index', compact('bloodRequests', 'needsProfile'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role->name ?? '', ['admin', 'rs', 'rumah_sakit'])) {
            abort(403, 'Hanya Admin dan Rumah Sakit yang dapat membuat permintaan darah.');
        }
        
        $hospital = null;
        $hospitals = collect();

        if (in_array($user->role->name ?? '', ['rs', 'rumah_sakit'])) {
            $hospital = $this->getHospitalForUser($user);
            if (!$hospital) {
                return redirect()->route('profile')->with('info', 'Silakan lengkapi profil rumah sakit Anda terlebih dahulu.');
            }
        } else {
            $hospitals = Hospital::all();
        }

        return view('blood_requests.create', compact('hospital', 'hospitals'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role->name ?? '', ['admin', 'rs', 'rumah_sakit'])) {
            abort(403, 'Hanya Admin dan Rumah Sakit yang dapat membuat permintaan darah.');
        }
        
        $hospitalId = null;

        if (in_array($user->role->name ?? '', ['rs', 'rumah_sakit'])) {
            $hospital = $this->getHospitalForUser($user);
            if (!$hospital) {
                return redirect()->route('profile')->with('info', 'Silakan lengkapi profil rumah sakit Anda terlebih dahulu.');
            }
            $hospitalId = $hospital->id;
        } else {
            // Admin must provide hospital_id
            $request->validate([
                'hospital_id' => 'required|exists:hospitals,id'
            ], [
                'hospital_id.required' => 'Rumah sakit wajib dipilih.',
                'hospital_id.exists' => 'Rumah sakit tidak valid.'
            ]);
            $hospitalId = $request->hospital_id;
        }

        $validated = $request->validate([
            'blood_type' => 'required|in:A,B,AB,O',
            'rhesus' => 'required|in:+,-',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ], [
            'blood_type.required' => 'Golongan darah wajib diisi.',
            'blood_type.in' => 'Golongan darah tidak valid.',
            'rhesus.required' => 'Rhesus wajib diisi.',
            'rhesus.in' => 'Rhesus tidak valid.',
            'quantity.required' => 'Jumlah kantong wajib diisi.',
            'quantity.integer' => 'Jumlah kantong harus berupa angka.',
            'quantity.min' => 'Jumlah kantong minimal 1.',
        ]);

        $validated['hospital_id'] = $hospitalId;
        $validated['request_date'] = now()->toDateString();
        $validated['status'] = 'Menunggu';
        $validated['request_code'] = 'REQ-' . date('YmdHis') . '-' . rand(100, 999);
        
        BloodRequest::create($validated);

        return redirect()->route('blood-requests.index')->with('success', 'Permintaan darah berhasil dibuat.');
    }

    public function show(BloodRequest $bloodRequest)
    {
        $user = Auth::user();
        if ($user->hasRole(['rs', 'rumah_sakit'])) {
            $hospital = $this->getHospitalForUser($user);
            if (!$hospital || $bloodRequest->hospital_id !== $hospital->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }
        
        return view('blood_requests.show', compact('bloodRequest'));
    }

    public function edit(BloodRequest $bloodRequest)
    {
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'petugas'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status.');
        }
        
        return view('blood_requests.edit', compact('bloodRequest'));
    }

    public function update(Request $request, BloodRequest $bloodRequest)
    {
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'petugas'])) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai,Ditolak',
        ], [
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid.',
        ]);

        $bloodRequest->update($validated);

        return redirect()->route('blood-requests.index')->with('success', 'Status permintaan berhasil diperbarui.');
    }

    public function destroy(BloodRequest $bloodRequest)
    {
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat menghapus permintaan darah.');
        }
        
        $bloodRequest->delete();

        return redirect()->route('blood-requests.index')->with('success', 'Permintaan darah berhasil dihapus.');
    }
}
