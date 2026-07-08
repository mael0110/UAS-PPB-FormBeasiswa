<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MahasiswaApiController extends Controller
{
    // 1. API REGISTRASI MAHASISWA
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // KOREKSI: Mengubah max|255 menjadi max:255
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa', // Otomatis mendaftar sebagai aktor mahasiswa
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi Mahasiswa Berhasil!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    // 2. API LOGIN MAHASISWA
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('role', 'mahasiswa')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password akun mahasiswa salah.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }

    // 3. API MELIHAT DAFTAR KELAS CODING / BEASISWA YANG TERSEDIA
    public function getKelas()
    {
        $kelas = DB::table('kelas_coding')->select('id', 'nama_kelas', 'kuota')->get();

        return response()->json([
            'status' => 'success',
            'data' => $kelas
        ], 200);
    }

    // 4. API MAHASISWA MENGIRIM BERKAS PENDAFTARAN BEASISWA
    public function daftarBeasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kelas_coding_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Pilihan kelas coding wajib diisi.'], 422);
        }

        $user = $request->user(); // Mendapatkan data mahasiswa yang sedang login lewat token

        // Cek apakah mahasiswa ini sudah pernah mendaftar sebelumnya
        $sudahDaftar = DB::table('pendaftaran')->where('user_id', $user->id)->exists();
        if ($sudahDaftar) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah pernah mengirimkan berkas pendaftaran sebelumnya.'
            ], 400);
        }

        // Simpan data pendaftaran masuk ke database
        DB::table('pendaftaran')->insert([
            'user_id' => $user->id,
            'kelas_coding_id' => $request->kelas_coding_id,
            'status_seleksi' => 'Pending', // Default awal perlu di-review admin
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berkas pendaftaran beasiswa Anda berhasil dikirim ke Admin Poliban!'
        ], 201);
    }

    // 5. API LOGOUT MAHASISWA (TAMBAHAN)
    public function logout(Request $request)
    {
        // Menghapus token yang sedang aktif digunakan oleh perangkat mobile
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil logout dari perangkat mobile.'
        ], 200);
    }
}