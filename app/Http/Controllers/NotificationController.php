<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'petugas', 'rs', 'rumah_sakit'])) {
            abort(403);
        }

        $query = Notification::query();

        // If not admin/petugas, filter by target_role
        if ($user->hasRole(['rs', 'rumah_sakit'])) {
            $query->whereIn('target_role', ['all', 'rs', 'rumah_sakit']);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $notifications = $query->latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403);
        }

        return view('notifications.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string',
            'target_role' => 'required|in:all,petugas,rs,rumah_sakit',
            'status' => 'required|in:Aktif,Nonaktif',
        ], [
            'title.required' => 'Judul wajib diisi.',
            'title.max' => 'Judul maksimal 100 karakter.',
            'message.required' => 'Pesan wajib diisi.',
            'target_role.required' => 'Target Role wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        Notification::create($validated);

        return redirect()->route('notifications.index')->with('success', 'Notifikasi berhasil ditambahkan.');
    }
}
