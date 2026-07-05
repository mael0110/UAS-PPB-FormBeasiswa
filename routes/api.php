<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KelasCodingController; // Tambahkan ini di atas
use Illuminate\Support\Facades\Route;

// Route Autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route untuk mengambil daftar kelas beasiswa (Bisa diakses umum)
Route::get('/kelas-beasiswa', [KelasCodingController::class, 'index']);