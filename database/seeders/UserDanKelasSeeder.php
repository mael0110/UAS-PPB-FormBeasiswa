<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserDanKelasSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data user lama jika ada
        DB::table('users')->delete();
        DB::table('kelas_coding')->delete();

        // 2. Buat Akun Admin
        User::create([
            'name' => 'Admin Beasiswa',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 3. Buat Akun Pegawai
        User::create([
            'name' => 'Pegawai Beasiswa',
            'email' => 'pegawai@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
        ]);

        // 4. Buat Akun Mahasiswa
        User::create([
            'name' => 'Ahmad Dani',
            'email' => 'dani@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);

        // 5. Buat Data Pilihan Beasiswa Kelas Coding
        DB::table('kelas_coding')->insert([
            [
                'nama_kelas' => 'Beasiswa Prestasi Akademik',
                'deskripsi' => 'Ditujukan bagi mahasiswa dengan IPK minimal 3.75 dan aktif organisasi.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_kelas' => 'Beasiswa Kurang Mampu',
                'deskripsi' => 'Bantuan pendidikan bagi mahasiswa yang memiliki kendala finansial.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}