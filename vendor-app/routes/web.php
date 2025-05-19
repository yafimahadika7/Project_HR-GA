<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// Super Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\AdminDashboardController; // ✅ Tambahkan ini

// Admin Unit Controllers
use App\Http\Controllers\Unit\TransaksiController;
use App\Http\Controllers\Unit\VendorKontakController;
use App\Http\Controllers\Unit\UnitDashboardController; // (Jika nanti dibutuhkan)

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================
// Landing Page
// ==========================
// Route::get('/', function () {
//     return view('welcome');
// });

// ==========================
// Dashboard Redirect Based on Role
// ==========================

Route::get('/', function () {
    return redirect()->route('login'); // atau 'dashboard'
});

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
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // ✅ Ganti ke controller

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
});

// ==========================
// Auth Routes
// ==========================
require __DIR__.'/auth.php';