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

// Kelompok Rute Khusus Admin
Route::middleware(['auth', 'checkrole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/pendaftaran/{id}/update-status', [AdminDashboardController::class, 'updateStatus'])->name('admin.update_status');
    
    // Rute CRUD Master Kelas
    Route::post('/admin/kelas', [AdminDashboardController::class, 'storeKelas'])->name('admin.store_kelas');
    Route::put('/admin/kelas/{id}', [AdminDashboardController::class, 'updateKelas'])->name('admin.update_kelas');
    Route::delete('/admin/kelas/{id}', [AdminDashboardController::class, 'destroyKelas'])->name('admin.destroy_kelas');
});

// 3.5 Kelompok Rute Khusus Pegawai (Diproteksi Middleware Role)
Route::middleware(['auth', 'checkrole:pegawai'])->group(function () {
    Route::get('/pegawai/dashboard', [\App\Http\Controllers\Pegawai\PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
});

// 4. Pengaman Fitur Profil Bawaan Breeze (Mencegah Error Route NOT Defined)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Memanggil Autentikasi Breeze (Login/Register/Logout)
require __DIR__.'/auth.php';