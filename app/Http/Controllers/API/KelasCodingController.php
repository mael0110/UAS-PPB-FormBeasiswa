<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KelasCodingController extends Controller
{
    public function index()
    {
        // Mengambil semua data kelas coding dari database
        $kelas = DB::table('kelas_coding')->get();

        // Kembalikan dalam bentuk JSON agar bisa dibaca oleh Flutter
        return response()->json([
            'success' => true,
            'message' => 'Daftar kelas beasiswa berhasil diambil.',
            'data' => $kelas
        ], 200);
    }
}