<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodDistribution;

class BloodDistributionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'petugas', 'rs', 'rumah_sakit'])) {
            abort(403);
        }

        $query = BloodDistribution::with(['bloodRequest.hospital']);

        if ($user->hasRole(['rs', 'rumah_sakit'])) {
            $hospital = \App\Models\Hospital::where('user_id', $user->id)->first();
            if (!$hospital) {
                $hospital = \App\Models\Hospital::whereNull('user_id')->first();
                if ($hospital) {
                    $hospital->user_id = $user->id;
                    $hospital->save();
                }
            }
            if ($hospital) {
                $query->whereHas('bloodRequest', function($q) use ($hospital) {
                    $q->where('hospital_id', $hospital->id);
                });
            } else {
                // If somehow no hospital is linked, return empty
                $query->where('id', 0);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                  ->orWhereHas('bloodRequest.hospital', function($q2) use ($search) {
                      $q2->where('hospital_name', 'like', "%{$search}%");
                  });
            });
        }

        $distributions = $query->latest()->paginate(10);

        return view('blood_distributions.index', compact('distributions'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'petugas'])) {
            abort(403);
        }

        // Get blood requests with status 'Diproses'
        $bloodRequests = \App\Models\BloodRequest::with('hospital')->where('status', 'Diproses')->get();

        return view('blood_distributions.create', compact('bloodRequests'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'petugas'])) {
            abort(403);
        }

        $validated = $request->validate([
            'blood_request_id' => 'required|exists:blood_requests,id',
            'courier_name' => 'required|string|max:255',
            'distribution_date' => 'required|date',
        ], [
            'blood_request_id.required' => 'Permintaan darah wajib dipilih.',
            'blood_request_id.exists' => 'Permintaan darah tidak valid.',
            'courier_name.required' => 'Nama kurir wajib diisi.',
            'distribution_date.required' => 'Tanggal distribusi wajib diisi.',
        ]);

        $validated['distribution_code'] = 'DST-' . date('YmdHis') . '-' . rand(100, 999);
        $validated['status'] = 'Diproses';

        BloodDistribution::create($validated);

        return redirect()->route('blood-distributions.index')->with('success', 'Distribusi darah berhasil ditambahkan.');
    }

    public function edit(BloodDistribution $bloodDistribution)
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'petugas'])) {
            abort(403);
        }

        return view('blood_distributions.edit', compact('bloodDistribution'));
    }

    public function update(Request $request, BloodDistribution $bloodDistribution)
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'petugas'])) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:Diproses,Dikirim,Diterima',
        ]);

        $bloodDistribution->update($validated);

        return redirect()->route('blood-distributions.index')->with('success', 'Status distribusi berhasil diperbarui.');
    }

    public function show(BloodDistribution $bloodDistribution)
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'petugas', 'rs', 'rumah_sakit'])) {
            abort(403);
        }

        if ($user->hasRole(['rs', 'rumah_sakit'])) {
            $hospital = \App\Models\Hospital::where('user_id', $user->id)->first();
            if (!$hospital || $bloodDistribution->bloodRequest->hospital_id !== $hospital->id) {
                abort(403);
            }
        }

        return view('blood_distributions.show', compact('bloodDistribution'));
    }
}
