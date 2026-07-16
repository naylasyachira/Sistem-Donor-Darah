<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodStock;

class BloodStockController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas', 'rs', 'rumah_sakit']), 403, 'Anda tidak memiliki hak akses ke modul ini.');
        
        $search = $request->query('search');
        $blood_type = $request->query('blood_type');
        $rhesus = $request->query('rhesus');

        // Summary Statistics
        $totalKantong = BloodStock::sum('quantity_bag');
        $totalVolume = BloodStock::sum('total_volume_ml');
        $totalGolongan = BloodStock::where('quantity_bag', '>', 0)->distinct('blood_type')->count('blood_type');
        $terakhirDiperbarui = BloodStock::max('last_update');

        $bloodStocks = BloodStock::when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('blood_type', 'like', "%{$search}%")
                      ->orWhere('rhesus', 'like', "%{$search}%");
                });
            })
            ->when($blood_type, function ($query, $blood_type) {
                return $query->where('blood_type', $blood_type);
            })
            ->when($rhesus, function ($query, $rhesus) {
                return $query->where('rhesus', $rhesus);
            })
            ->latest('last_update')
            ->paginate(10);

        return view('blood_stocks.index', compact(
            'bloodStocks', 'search', 'blood_type', 'rhesus',
            'totalKantong', 'totalVolume', 'totalGolongan', 'terakhirDiperbarui'
        ));
    }

    public function show(BloodStock $bloodStock)
    {
        abort_if(!auth()->user()->hasRole(['admin', 'petugas', 'rs', 'rumah_sakit']), 403, 'Anda tidak memiliki hak akses ke modul ini.');

        // Fetch history of donations that contributed to this blood stock
        $history = \App\Models\Donation::with('donor')
            ->where('blood_type', $bloodStock->blood_type)
            ->where('rhesus', $bloodStock->rhesus)
            ->where('status', 'Berhasil')
            ->orderBy('donation_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('blood_stocks.show', compact('bloodStock', 'history'));
    }
}
