<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. FUNGSI REGISTER (Menu "Daftar Sekarang" di Flutter)
    public function register(Request $request)
    {
        // Validasi input dari Flutter
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Simpan ke database (Otomatis role: mahasiswa)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa', 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan kembali ke halaman login.',
        ], 201);
    }

    // 2. FUNGSI LOGIN
    public function login(Request $request)
    {
        // Validasi input email & password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek email dan kecocokan password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // Buat Token Sanctum untuk keamanan API Flutter
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kembalikan response sukses beserta DATA ROLE untuk dibaca Flutter
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role, // 'admin', 'pegawai', atau 'mahasiswa'
            ]
        ], 200);
    }
}