<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// Super Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Admin Unit Controllers
use App\Http\Controllers\Unit\TransaksiController;
use App\Http\Controllers\Unit\VendorKontakController;
use App\Http\Controllers\Unit\PemesananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================
// Landing Page Redirect
// ==========================
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================
// Dashboard Redirect Based on Role
// ==========================
Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->isAdmin()) {
            return redirect()->route('unit.dashboard');
        }
    }
    abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================
// Profile Management (All Roles)
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================
// SUPER ADMIN ROUTES
// ==========================
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('vendor', VendorController::class);
});

// ==========================
// ADMIN UNIT ROUTES
// ==========================
Route::middleware(['auth', 'role:admin'])->prefix('unit')->name('unit.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('vendor', [VendorKontakController::class, 'index'])->name('vendor.index');
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

    // Pemesanan Vendor
    Route::get('pemesanan', [PemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('pemesanan/create/{vendor}', [PemesananController::class, 'create'])->name('pemesanan.create');
    Route::post('pemesanan/store/{vendor}', [PemesananController::class, 'store'])->name('pemesanan.store');
    Route::post('pemesanan/kirim-email/{vendor}', [PemesananController::class, 'kirimEmail'])->name('pemesanan.kirimEmail');
});

// ==========================
// Auth Routes (Laravel Breeze / Jetstream / Fortify)
// ==========================
require __DIR__.'/auth.php';
