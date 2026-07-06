<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Pengaman otomatis jika tabel database buatanmu belum terisi data pendaftaran
        $totalPendaftar = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->count() : 0;
        $totalPending = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->where('status_seleksi', 'Pending')->count() : 0;
        $totalDiterima = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->where('status_seleksi', 'Diterima')->count() : 0;
        
        $pendaftaranTerbaru = [];
        if (Schema::hasTable('pendaftaran') && Schema::hasTable('users') && Schema::hasTable('kelas_coding')) {
            $pendaftaranTerbaru = DB::table('pendaftaran')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->join('kelas_coding', 'pendaftaran.kelas_coding_id', '=', 'kelas_coding.id')
                ->select('pendaftaran.*', 'users.name as nama_mahasiswa', 'kelas_coding.nama_kelas')
                ->orderBy('pendaftaran.created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('admin.dashboard', compact('totalPendaftar', 'totalPending', 'totalDiterima', 'pendaftaranTerbaru'));
    }
}