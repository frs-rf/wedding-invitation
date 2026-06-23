<?php

use App\Enums\Role;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Vendor\ClientController;

/**
 * Dashboard router berbasis role.
 * Setelah login, user diarahkan ke dashboard sesuai role-nya.
 */
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Public Phase 5 Routes
Route::get('/invitation/{slug}', [\App\Http\Controllers\InvitationController::class, 'show'])->name('invitation.show');
Route::get('/check-in/{slug}', [\App\Http\Controllers\CheckInController::class, 'index'])->name('check-in.index');
Route::post('/check-in/{slug}/process', [\App\Http\Controllers\CheckInController::class, 'process'])->name('check-in.process');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Impersonate Stop (Tersedia untuk Pengantin yang sedang di-impersonate)
    Route::post('/impersonate/stop', function () {
        if (session()->has('impersonated_by')) {
            $vendorId = session('impersonated_by');
            $vendor = \App\Models\User::find($vendorId);
            if ($vendor && $vendor->isVendor()) {
                auth()->login($vendor);
                session()->forget('impersonated_by');
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('dashboard');
    })->name('impersonate.stop');

    // Vendor Routes
    Route::middleware('role:Vendor')->prefix('vendor')->name('vendor.')->group(function () {
        Route::resource('clients', ClientController::class)->except(['show', 'edit', 'update', 'destroy']);
        Route::post('clients/{client}/impersonate', [ClientController::class, 'impersonate'])->name('clients.impersonate');
    });

    // Pengantin Routes
    Route::middleware('role:Pengantin')->prefix('pengantin')->name('pengantin.')->group(function () {
        Route::post('generate-token', [\App\Http\Controllers\DashboardController::class, 'generateToken'])->name('generate-token');
        Route::get('themes', [\App\Http\Controllers\Pengantin\ThemeController::class, 'index'])->name('themes.index');
        Route::put('themes', [\App\Http\Controllers\Pengantin\ThemeController::class, 'update'])->name('themes.update');

        Route::get('guests/template', [\App\Http\Controllers\Pengantin\GuestController::class, 'downloadTemplate'])->name('guests.template');
        Route::post('guests/import', [\App\Http\Controllers\Pengantin\GuestController::class, 'import'])->name('guests.import');
        Route::resource('guests', \App\Http\Controllers\Pengantin\GuestController::class)->except(['create', 'show', 'edit']);
    });
});

require __DIR__.'/auth.php';

// UI Preview Routes (Added for Stitch AI Design Review)
Route::prefix('ui-preview')->group(function () {
    Route::get('/vendor/dashboard', function () {
        return Inertia::render('Vendor/Dashboard');
    })->name('preview.vendor.dashboard');

    Route::get('/couple/dashboard', function () {
        return Inertia::render('Couple/Dashboard');
    })->name('preview.couple.dashboard');

    Route::get('/couple/guests', function () {
        return Inertia::render('Couple/GuestList');
    })->name('preview.couple.guests');

    Route::get('/receptionist/scanner', function () {
        return Inertia::render('Receptionist/Scanner');
    })->name('preview.receptionist.scanner');
});
