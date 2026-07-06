<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama Beranda
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard Router Automatis (Penentu arah setelah Login)
Route::get('/dashboard', function () {
    // Jika login sebagai admin, arahkan ke dashboard admin
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    // Jika pegawai, arahkan ke dashboard pegawai (nanti bisa dibuat)
    if (auth()->user()->role === 'pegawai') {
        return redirect()->to('/pegawai/dashboard');
    }
    // Jika mahasiswa/umum
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Kelompok Rute Khusus Admin (Diproteksi Middleware Role)
Route::middleware(['auth', 'checkrole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// 4. Pengaman Fitur Profil Bawaan Breeze (Mencegah Error Route NOT Defined)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Memanggil Autentikasi Breeze (Login/Register/Logout)
require __DIR__.'/auth.php';