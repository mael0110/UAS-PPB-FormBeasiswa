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

        // Hapus filter where('role', 'mahasiswa') agar Admin/Pegawai bisa ditemukan
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ada DAN password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.' // Pesan lebih netral
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user // User ini sekarang membawa info 'role': 'admin' dll
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
    // Validasi harus mencakup SEMUA kolom di tabel
    $request->validate([
        'kelas_coding_id' => 'required',
        'nisn' => 'required',
        'nama' => 'required',
        'alamat' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required',
        'asal_sekolah' => 'required',
        'jenis_kelamin' => 'required',
        'jurusan' => 'required',
        'nama_ayah' => 'required',
        'pekerjaan_ayah' => 'required',
        'nama_ibu' => 'required',
        'pekerjaan_ibu' => 'required',
        'penghasilan_orang_tua' => 'required',
        'foto' => 'required|image',
    ]);

    // Simpan foto ke folder storage/app/public/pendaftaran_berkas
    $path = $request->file('foto')->store('pendaftaran_berkas', 'public');

    // Simpan ke database
    DB::table('pendaftaran')->insert([
        'user_id' => $request->user()->id,
        'kelas_coding_id' => $request->kelas_coding_id,
        'nisn' => $request->nisn,
        'nama' => $request->nama,
        'alamat' => $request->alamat,
        'tempat_lahir' => $request->tempat_lahir,
        'tanggal_lahir' => $request->tanggal_lahir,
        'asal_sekolah' => $request->asal_sekolah,
        'jenis_kelamin' => $request->jenis_kelamin,
        'jurusan' => $request->jurusan,
        'nama_ayah' => $request->nama_ayah,
        'pekerjaan_ayah' => $request->pekerjaan_ayah,
        'nama_ibu' => $request->nama_ibu,
        'pekerjaan_ibu' => $request->pekerjaan_ibu,
        'penghasilan_orang_tua' => $request->penghasilan_orang_tua,
        'foto' => $path,
        'status_seleksi' => 'Pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['status' => 'success', 'message' => 'Pendaftaran berhasil!'], 201);
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

    public function getStatusPendaftaran(Request $request)
    {
        $pendaftaran = DB::table('pendaftaran')
            ->join('kelas_coding', 'pendaftaran.kelas_coding_id', '=', 'kelas_coding.id')
            ->where('pendaftaran.user_id', $request->user()->id)
            ->select('pendaftaran.status_seleksi', 'kelas_coding.nama_kelas')
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $pendaftaran
        ]);
    }

    public function getProfil(Request $request)
    {
        // Mengambil data dengan menggabungkan tabel users dan pendaftaran
        $data = DB::table('users')
                ->leftJoin('pendaftaran', 'users.id', '=', 'pendaftaran.user_id')
                ->select('users.name', 'pendaftaran.*') // Mengambil nama dari users dan semua field pendaftaran
                ->where('users.id', $request->user()->id)
                ->first();

        return response()->json([
            'status' => 'success',
            'data' => $data // Sekarang data ini akan berisi nama user dan data pendaftarannya
        ], 200);
    }
}