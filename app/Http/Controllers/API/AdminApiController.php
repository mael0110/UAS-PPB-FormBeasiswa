<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Log;

class AdminApiController extends Controller
{
    // ==========================================
    // --- 1. CRUD MANAJEMEN KELAS ---
    // ==========================================
    
    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255', 
            'kuota' => 'required|integer|min:1'
        ]);

        try {
            if (!Schema::hasTable('kelas_coding')) {
                return response()->json(['status' => 'error', 'message' => 'Tabel kelas_coding tidak ditemukan'], 404);
            }

            DB::table('kelas_coding')->insert([
                'nama_kelas' => $request->nama_kelas,
                'kuota' => $request->kuota,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Kelas berhasil ditambah'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambah kelas: ' . $e->getMessage()], 500);
        }
    }

    public function updateKelas(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255', 
            'kuota' => 'required|integer|min:1'
        ]);

        try {
            if (!Schema::hasTable('kelas_coding')) {
                return response()->json(['status' => 'error', 'message' => 'Tabel kelas_coding tidak ditemukan'], 404);
            }

            DB::table('kelas_coding')->where('id', $id)->update([
                'nama_kelas' => $request->nama_kelas,
                'kuota' => $request->kuota,
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Kelas berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengupdate kelas: ' . $e->getMessage()], 500);
        }
    }

    public function destroyKelas($id)
    {
        try {
            if (!Schema::hasTable('kelas_coding')) {
                return response()->json(['status' => 'error', 'message' => 'Tabel kelas_coding tidak ditemukan'], 404);
            }

            DB::table('kelas_coding')->where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Kelas dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus kelas: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // --- 2. CRUD VALIDASI PENDAFTARAN (ANTI ERROR 500) ---
    // ==========================================
    
    public function getPendaftaran()
    {
        try {
            // Hitung statistik ringkas untuk widget panel atas Flutter
            $totalPendaftar = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->count() : 0;
            $totalPending = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->where('status_seleksi', 'Pending')->count() : 0;
            $totalDiterima = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->where('status_seleksi', 'Diterima')->count() : 0;

            $data = [];
            if (Schema::hasTable('pendaftaran')) {
                $query = DB::table('pendaftaran');

                // Lakukan join jika tabel relasinya tersedia
                if (Schema::hasTable('users') && Schema::hasColumn('pendaftaran', 'user_id')) {
                    $query->join('users', 'pendaftaran.user_id', '=', 'users.id');
                }
                if (Schema::hasTable('kelas_coding') && Schema::hasColumn('pendaftaran', 'kelas_coding_id')) {
                    $query->join('kelas_coding', 'pendaftaran.kelas_coding_id', '=', 'kelas_coding.id');
                }

                // Pilih select field secara dinamis agar aman dari kolom kosong
                $selectFields = ['pendaftaran.id'];
                if (Schema::hasColumn('pendaftaran', 'status_seleksi')) $selectFields[] = 'pendaftaran.status_seleksi';
                if (Schema::hasColumn('pendaftaran', 'nisn')) $selectFields[] = 'pendaftaran.nisn';
                
                // Kondisional alias data join
                if (Schema::hasTable('users')) $selectFields[] = 'users.name as nama_mahasiswa';
                if (Schema::hasTable('kelas_coding')) $selectFields[] = 'kelas_coding.nama_kelas as beasiswa_diambil';

                $data = $query->select($selectFields)->orderBy('pendaftaran.id', 'desc')->get()->map(function($item) {
                    return [
                        'id' => $item->id,
                        'status_seleksi' => $item->status_seleksi ?? 'Pending',
                        'nama_mahasiswa' => $item->nama_mahasiswa ?? 'User Tanpa Nama',
                        'nisn' => $item->nisn ?? '-',
                        'beasiswa_diambil' => $item->beasiswa_diambil ?? 'Kelas Default',
                    ];
                });
            }

            // Ambil juga list kelas master untuk halaman Manajemen Kelas Coding
            $allKelas = Schema::hasTable('kelas_coding') ? DB::table('kelas_coding')->orderBy('id', 'desc')->get() : [];

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_pendaftar' => $totalPendaftar,
                    'total_pending' => $totalPending,
                    'total_diterima' => $totalDiterima,
                    'pendaftaran_list' => $data,
                    'kelas_list' => $allKelas
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Admin API Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat data dashboard pendaftaran: ' . $e->getMessage(),
                'data' => [
                    'total_pendaftar' => 0, 'total_pending' => 0, 'total_diterima' => 0,
                    'pendaftaran_list' => [], 'kelas_list' => []
                ]
            ], 200); // Kembalikan data kosong agar Flutter tidak crash/loading terus
        }
    }

    public function updateStatusPendaftaran(Request $request, $id)
    {
        $request->validate(['status_seleksi' => 'required|in:Diterima,Ditolak,Pending']);

        try {
            if (!Schema::hasTable('pendaftaran')) {
                return response()->json(['status' => 'error', 'message' => 'Tabel pendaftaran tidak ditemukan'], 404);
            }

            DB::table('pendaftaran')->where('id', $id)->update([
                'status_seleksi' => $request->status_seleksi,
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Status pendaftaran diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }

    public function deletePendaftaran($id)
    {
        try {
            if (!Schema::hasTable('pendaftaran')) {
                return response()->json(['status' => 'error', 'message' => 'Tabel pendaftaran tidak ditemukan'], 404);
            }

            DB::table('pendaftaran')->where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data pendaftaran dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus pendaftaran: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // --- 3. CRUD MANAJEMEN PENGGUNA ---
    // ==========================================
    
    public function getAllUsers()
    {
        try {
            return response()->json(['status' => 'success', 'data' => User::all()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengambil user: ' . $e->getMessage()], 500);
        }
    }

    public function deleteUser($id)
    {
        try {
            User::destroy($id);
            return response()->json(['status' => 'success', 'message' => 'Pengguna berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus pengguna: ' . $e->getMessage()], 500);
        }
    }
}