<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        // Pegawai bisa melihat statistik pendaftaran beasiswa
        $totalPendaftar = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->count() : 0;
        $totalDiterima = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->where('status_seleksi', 'Diterima')->count() : 0;
        
        // Mengambil data pendaftaran khusus untuk dipantau oleh pegawai
        $allPendaftaran = [];
        if (Schema::hasTable('pendaftaran') && Schema::hasTable('users')) {
            $allPendaftaran = DB::table('pendaftaran')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->select('pendaftaran.*', 'users.name as nama_mahasiswa', 'users.email as email_mahasiswa')
                ->orderBy('pendaftaran.created_at', 'desc')
                ->get();
        }

        return view('pegawai.dashboard', compact('totalPendaftar', 'totalDiterima', 'allPendaftaran'));
    }
}