<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Menghitung total pendaftar berdasarkan statusnya
        $totalPendaftar = DB::table('pendaftaran')->count();
        $totalPending = DB::table('pendaftaran')->where('status_seleksi', 'Pending')->count();
        $totalDiterima = DB::table('pendaftaran')->where('status_seleksi', 'Diterima')->count();
        
        // Mengambil data pendaftaran terbaru untuk ditampilkan di tabel
        $pendaftaranTerbaru = DB::table('pendaftaran')
            ->join('users', 'pendaftaran.user_id', '=', 'users.id')
            ->join('kelas_coding', 'pendaftaran.kelas_coding_id', '=', 'kelas_coding.id')
            ->select('pendaftaran.*', 'users.name as nama_mahasiswa', 'kelas_coding.nama_kelas')
            ->orderBy('pendaftaran.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('totalPendaftar', 'totalPending', 'totalDiterima', 'pendaftaranTerbaru'));
    }
}