<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelas_coding_id')->constrained('kelas_coding');
            $table->string('nisn', 20);
            $table->string('asal_sekolah');
            $table->string('nama_orang_tua');
            $table->string('pekerjaan_orang_tua');
            $table->unsignedBigInteger('penghasilan_orang_tua');
            $table->string('foto'); 
            $table->enum('status_seleksi', ['Pending', 'Lolos Berkas', 'Diterima', 'Ditolak', 'Revisi'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pendaftaran');
    }
};