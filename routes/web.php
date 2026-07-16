<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && (Hash::check($credentials['password'], $user->password) || $user->password === $credentials['password'])) {
            Auth::login($user, $remember);
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Berhasil masuk ke aplikasi RedPulse.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    })->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil keluar dari aplikasi.');
    })->name('logout');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        $stats = [];
        
        if ($user->hasRole('admin')) {
            $stats['total_pendonor'] = \App\Models\Donor::count();
            $stats['total_user'] = \App\Models\User::count();
            $stats['total_rumah_sakit'] = \App\Models\User::whereHas('role', function($q) {
                $q->where('name', 'rumah_sakit');
            })->count();
            $stats['pendonor_aktif'] = \App\Models\Donor::where('status', 'aktif')->count();
            $stats['pendonor_laki'] = \App\Models\Donor::where('jenis_kelamin', 'L')->count();
            $stats['pendonor_perempuan'] = \App\Models\Donor::where('jenis_kelamin', 'P')->count();
        } elseif ($user->hasRole('petugas')) {
            $stats['total_pendonor'] = \App\Models\Donor::count();
            $stats['pendonor_hari_ini'] = \App\Models\Donor::whereDate('created_at', \Carbon\Carbon::today())->count();
            $stats['pendonor_aktif'] = \App\Models\Donor::where('status', 'aktif')->count();
        } elseif ($user->hasRole('rumah_sakit')) {
            $stats['total_permintaan'] = 0;
            $stats['permintaan_diproses'] = 0;
            $stats['permintaan_disetujui'] = 0;
            $stats['permintaan_ditolak'] = 0;
        }

        return view('dashboard.index', compact('stats'));
    })->name('dashboard');

    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('donors', \App\Http\Controllers\DonorController::class)->middleware('role:admin,petugas');
    Route::resource('screenings', \App\Http\Controllers\ScreeningController::class)->middleware('role:admin,petugas');
    Route::resource('donations', \App\Http\Controllers\DonationController::class)->middleware('role:admin,petugas');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    Route::get('/settings', function () {
        return view('profile.settings');
    })->name('settings');

    Route::post('/settings', function (Request $request) {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    })->name('settings.update');
});
