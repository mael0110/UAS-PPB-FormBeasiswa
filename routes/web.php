<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 1. Halaman Utama Beranda
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard Router Automatis (Penentu arah setelah Login)
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->role === 'pegawai') {
        return redirect()->to('/pegawai/dashboard');
    }
    // Jika mahasiswa, arahkan ke rute dashboard mahasiswa
    if (auth()->user()->role === 'mahasiswa') {
        return redirect()->route('mahasiswa.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Kelompok Rute Khusus Admin
Route::middleware(['auth', 'checkrole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/pendaftaran/{id}/update-status', [AdminDashboardController::class, 'updateStatus'])->name('admin.update_status');
    
    // Rute CRUD Master Kelas
    Route::post('/admin/kelas', [AdminDashboardController::class, 'storeKelas'])->name('admin.store_kelas');
    Route::put('/admin/kelas/{id}', [AdminDashboardController::class, 'updateKelas'])->name('admin.update_kelas');
    Route::delete('/admin/kelas/{id}', [AdminDashboardController::class, 'destroyKelas'])->name('admin.destroy_kelas');
});

// 3.5 Kelompok Rute Khusus Pegawai (Diproteksi Middleware Role)
Route::middleware(['auth', 'checkrole:pegawai'])->group(function () {
    Route::get('/pegawai/dashboard', [\App\Http\Controllers\Pegawai\PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
});

Route::middleware(['auth', 'checkrole:mahasiswa'])->group(function () {
    
    // 1. Dashboard Utama Mahasiswa (Menampilkan Daftar Kartu Beasiswa)
    Route::get('/mahasiswa/dashboard', function () {
        $allKelas = DB::table('kelas_coding')->get();
        $pendaftaran = DB::table('pendaftaran')
            ->where('user_id', auth()->id())
            ->first();

        return view('mahasiswa.dashboard', compact('allKelas', 'pendaftaran'));
    })->name('mahasiswa.dashboard');

    // 2. Halaman Formulir Pendaftaran (Sesuai dengan file migrasi kamu)
    Route::get('/mahasiswa/daftar/{kelas_id}', function ($kelas_id) {
        $sudahDaftar = DB::table('pendaftaran')->where('user_id', auth()->id())->exists();
        if ($sudahDaftar) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda sudah mendaftar.');
        }

        $kelas = DB::table('kelas_coding')->where('id', $kelas_id)->first();
        if (!$kelas) abort(404);

        return view('mahasiswa.formulir', compact('kelas'));
    })->name('mahasiswa.formulir');

    // 3. Proses Simpan Formulir ke Database
    Route::post('/mahasiswa/simpan', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'kelas_coding_id' => 'required',
            'nisn' => 'required|max:20',
            'nama' => 'required|string|max:255', // Ditambahkan agar nama bisa diinput
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'asal_sekolah' => 'required',
            'jenis_kelamin' => 'required',
            'jurusan' => 'required',
            'nama_ayah' => 'required',
            'pekerjaan_ayah' => 'required',
            'nama_ibu' => 'required',
            'pekerjaan_ibu' => 'required',
            'penghasilan_orang_tua' => 'required|numeric',
            'foto' => 'required|image|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_pendaftar', 'public');
        }

        // COCOK 100% DENGAN STRUKTUR MIGRASI SOAL UAS KAMU
        DB::table('pendaftaran')->insert([
            'user_id' => auth()->id(),
            'kelas_coding_id' => $request->kelas_coding_id,
            'nisn' => $request->nisn,
            'nama' => $request->nama, // Menyimpan inputan nama manual
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
            'foto' => $fotoPath,
            'status_seleksi' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Formulir pendaftaran berhasil dikirim!');
    })->name('mahasiswa.simpan');
});

// 4. Pengaman Fitur Profil Bawaan Breeze (Mencegah Error Route NOT Defined)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Memanggil Autentikasi Breeze (Login/Register/Logout)
require __DIR__.'/auth.php';