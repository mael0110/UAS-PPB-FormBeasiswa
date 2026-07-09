<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiApiController extends Controller
{
    public function getDashboardData()
    {
        try {
            // 1. Ambil data pendaftaran langsung
            $pendaftaranRaw = DB::table('pendaftaran')->get();
            $totalPendaftar = $pendaftaranRaw->count();
            
            // Hitung yang statusnya Diterima / Terverifikasi (case-insensitive)
            $totalTerverifikasi = DB::table('pendaftaran')
                ->whereIn(DB::raw('LOWER(status_seleksi)'), ['diterima', 'terverifikasi', 'success'])
                ->count();

            $allPendaftaran = [];

            // 2. Susun data untuk dilempar ke Flutter
            foreach ($pendaftaranRaw as $row) {
                $user = DB::table('users')->where('id', $row->user_id ?? null)->first();
                $namaMahasiswa = $user ? $user->name : 'Mahasiswa Tanpa Nama';

                $allPendaftaran[] = [
                    'id' => $row->id,
                    'nama_lengkap' => $namaMahasiswa,
                    'beasiswa_yang_diambil' => $row->beasiswa_yang_diambil ?? ($row->jenis_beasiswa ?? 'Full Stuck'),
                    'nisn' => $row->nisn ?? '-',
                    'status_kelayakan' => $row->status_seleksi ?? ($row->status ?? 'PENDING')
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_pendaftar' => $totalPendaftar,
                    'total_terverifikasi' => $totalTerverifikasi,
                    'pendaftaran_list' => $allPendaftaran
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Detail Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // --- METHOD INI YANG HILANG DAN HARUS DIKEMBALIKAN ---
    public function updateStatus(Request $request, $id)
    {
        try {
            // Karena Flutter mengirimkan 'PENDING', 'Diterima', atau 'DITOLAK'
            $statusBaru = $request->input('status_kelayakan');

            if (!$statusBaru) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Status tidak boleh kosong.'
                ], 400);
            }

            // Update ke tabel pendaftaran pada kolom status_seleksi
            $updated = DB::table('pendaftaran')
                ->where('id', $id)
                ->update(['status_seleksi' => $statusBaru]);

            return response()->json([
                'status' => 'success',
                'message' => 'Status pendaftaran berhasil diperbarui ke ' . $statusBaru
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui status di Backend: ' . $e->getMessage()
            ], 500);
        }
    }
}