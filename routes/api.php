<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MahasiswaApiController;

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
    Route::post('/logout', [MahasiswaApiController::class, 'logout']);
    
    // Cek profil mahasiswa yang sedang login
    Route::get('/user-profile', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'user' => $request->user()
        ]);
    });
});