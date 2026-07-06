<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'super@admin.poliban.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin', // Memastikan middleware 'checkrole:admin' mengizinkan masuk
        ]);

        // 2. Akun Pegawai
        User::create([
            'name' => 'Pegawai Akademik',
            'email' => 'staf@pegawai.poliban.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
        ]);

        // 3. Akun Mahasiswa
        User::create([
            'name' => 'Budi Mahasiswa',
            'email' => 'budi@mahasiswa.poliban.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);
    }
}