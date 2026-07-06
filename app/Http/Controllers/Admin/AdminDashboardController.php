<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalPendaftar = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->count() : 0;
        $totalPending = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->where('status_seleksi', 'Pending')->count() : 0;
        $totalDiterima = Schema::hasTable('pendaftaran') ? DB::table('pendaftaran')->where('status_seleksi', 'Diterima')->count() : 0;
        
        $allPendaftaran = [];
        if (Schema::hasTable('pendaftaran') && Schema::hasTable('users')) {
            $allPendaftaran = DB::table('pendaftaran')
                ->join('users', 'pendaftaran.user_id', '=', 'users.id')
                ->select('pendaftaran.*', 'users.name as nama_mahasiswa', 'users.email as email_mahasiswa')
                ->orderBy('pendaftaran.created_at', 'desc')
                ->get();
        }

        // READ: Mengambil semua data master kelas coding
        $allKelas = [];
        if (Schema::hasTable('kelas_coding')) {
            $allKelas = DB::table('kelas_coding')->orderBy('created_at', 'desc')->get();
        }

        return view('admin.dashboard', compact('totalPendaftar', 'totalPending', 'totalDiterima', 'allPendaftaran', 'allKelas'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status_seleksi' => 'required|in:Pending,Diterima,Ditolak']);
        if (Schema::hasTable('pendaftaran')) {
            DB::table('pendaftaran')->where('id', $id)->update(['status_seleksi' => $request->status_seleksi, 'updated_at' => now()]);
            return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui!');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui status.');
    }

    // CREATE: Tambah Kelas Baru
    public function storeKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
        ]);

        if (Schema::hasTable('kelas_coding')) {
            DB::table('kelas_coding')->insert([
                'nama_kelas' => $request->nama_kelas,
                'kuota' => $request->kuota,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Kelas baru berhasil ditambahkan!');
        }
        return redirect()->back()->with('error', 'Tabel kelas_coding tidak ditemukan.');
    }

    // UPDATE: Ubah Data Kelas
    public function updateKelas(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
        ]);

        if (Schema::hasTable('kelas_coding')) {
            DB::table('kelas_coding')->where('id', $id)->update([
                'nama_kelas' => $request->nama_kelas,
                'kuota' => $request->kuota,
                'updated_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Data kelas berhasil diubah!');
        }
        return redirect()->back()->with('error', 'Gagal mengubah data kelas.');
    }

    // DELETE: Hapus Kelas
    public function destroyKelas($id)
    {
        if (Schema::hasTable('kelas_coding')) {
            DB::table('kelas_coding')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Kelas berhasil dihapus dari sistem!');
        }
        return redirect()->back()->with('error', 'Gagal menghapus kelas.');
    }
}