<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminApiController extends Controller
{
    // --- 1. CRUD MANAJEMEN KELAS ---
    public function storeKelas(Request $request)
    {
        $request->validate(['nama_kelas' => 'required', 'kuota' => 'required|integer']);
        DB::table('kelas_coding')->insert([
            'nama_kelas' => $request->nama_kelas,
            'kuota' => $request->kuota,
            'created_at' => now()
        ]);
        return response()->json(['message' => 'Kelas berhasil ditambah'], 201);
    }

    public function updateKelas(Request $request, $id)
    {
        DB::table('kelas_coding')->where('id', $id)->update([
            'nama_kelas' => $request->nama_kelas,
            'kuota' => $request->kuota,
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'Kelas berhasil diupdate']);
    }

    public function destroyKelas($id)
    {
        DB::table('kelas_coding')->where('id', $id)->delete();
        return response()->json(['message' => 'Kelas dihapus']);
    }

    // --- 2. CRUD VALIDASI PENDAFTARAN ---
    public function getPendaftaran()
    {
        $data = DB::table('pendaftaran')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('kelas_coding', 'pendaftaran.kelas_coding_id', '=', 'kelas_coding.id')
            ->select('pendaftaran.*', 'users.name as nama_mahasiswa', 'kelas_coding.nama_kelas')
            ->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function updateStatusPendaftaran(Request $request, $id)
    {
        $request->validate(['status_seleksi' => 'required|in:Diterima,Ditolak,Pending']);
        DB::table('pendaftaran')->where('id', $id)->update(['status_seleksi' => $request->status_seleksi]);
        return response()->json(['message' => 'Status pendaftaran diupdate']);
    }

    public function deletePendaftaran($id)
    {
        DB::table('pendaftaran')->where('id', $id)->delete();
        return response()->json(['message' => 'Data pendaftaran dihapus']);
    }

    // --- 3. CRUD MANAJEMEN PENGGUNA ---
    public function getAllUsers()
    {
        return response()->json(['data' => User::all()]);
    }

    public function deleteUser($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'Pengguna berhasil dihapus']);
    }
}