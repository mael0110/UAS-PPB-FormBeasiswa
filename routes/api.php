<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\PegawaiApiController;

/*
|--------------------------------------------------------------------------
| Jalur API - Regal Academy Scholarship
|--------------------------------------------------------------------------
*/

// Rute Publik (Bisa diakses Flutter tanpa login)
Route::post('/register', [MahasiswaApiController::class, 'register']);
Route::post('/login', [MahasiswaApiController::class, 'login']);
Route::get('/kelas-beasiswa', [MahasiswaApiController::class, 'getKelas']);

// Rute Terproteksi (Aplikasi Flutter wajib menyertakan Bearer Token hasil login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/daftar-beasiswa', [MahasiswaApiController::class, 'daftarBeasiswa']);
    Route::get('/status-pendaftaran', [MahasiswaApiController::class, 'getStatusPendaftaran']);
    Route::post('/logout', [MahasiswaApiController::class, 'logout']);
    Route::get('/kelas', [MahasiswaApiController::class, 'getKelas']);
    Route::get('/profil-mahasiswa', [MahasiswaApiController::class, 'getProfil']);
    Route::get('/tes-koneksi', function() {
    return response()->json(['message' => 'Koneksi Berhasil']);
});
    
    // Cek profil mahasiswa yang sedang login
    Route::get('/user-profile', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'user' => $request->user()
        ]);
    });

    // 1. CRUD Kelas (Beasiswa)
    Route::post('/admin/kelas', [AdminApiController::class, 'storeKelas']);
    Route::put('/admin/kelas/{id}', [AdminApiController::class, 'updateKelas']);
    Route::delete('/admin/kelas/{id}', [AdminApiController::class, 'destroyKelas']);

    // 2. CRUD Validasi Pendaftaran
    Route::get('/admin/pendaftaran', [AdminApiController::class, 'getPendaftaran']);
    Route::put('/admin/pendaftaran/{id}/status', [AdminApiController::class, 'updateStatusPendaftaran']);
    Route::delete('/admin/pendaftaran/{id}', [AdminApiController::class, 'deletePendaftaran']);

    // 3. CRUD Manajemen User
    Route::get('/admin/users', [AdminApiController::class, 'getAllUsers']);
    Route::delete('/admin/users/{id}', [AdminApiController::class, 'deleteUser']);

    //pegawai
    Route::get('/pegawai/dashboard', [PegawaiApiController::class, 'getDashboardData']);
    Route::put('/pegawai/pendaftaran/{id}/status', [PegawaiApiController::class, 'updateStatus']);
});
