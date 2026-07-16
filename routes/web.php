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
        } elseif ($user->hasRole(['rs', 'rumah_sakit'])) {
            $stats['total_golongan_tersedia'] = \App\Models\BloodStock::where('quantity_bag', '>', 0)->distinct('blood_type')->count('blood_type');
            $stats['total_kantong'] = \App\Models\BloodStock::sum('quantity_bag');
            $stats['total_volume'] = \App\Models\BloodStock::sum('total_volume_ml');
            $stats['terakhir_diperbarui'] = \App\Models\BloodStock::max('last_update');
        }

        return view('dashboard.index', compact('stats'));
    })->name('dashboard');

    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('donors', \App\Http\Controllers\DonorController::class)->middleware('role:admin,petugas,rumah_sakit');
    Route::resource('screenings', \App\Http\Controllers\ScreeningController::class)->middleware('role:admin,petugas,rumah_sakit');
    Route::resource('donations', \App\Http\Controllers\DonationController::class)->middleware('role:admin,petugas,rumah_sakit');
    Route::resource('blood-stocks', \App\Http\Controllers\BloodStockController::class)->middleware('role:admin,petugas,rumah_sakit');
    Route::resource('hospitals', \App\Http\Controllers\HospitalController::class)->middleware('role:admin,petugas,rumah_sakit');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    Route::get('/settings', function () {
        return view('profile.settings');
    })->name('settings');

    Route::post('/settings/profile', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    })->name('settings.profile.update');

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
