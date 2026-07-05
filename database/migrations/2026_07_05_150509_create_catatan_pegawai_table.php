<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('catatan_pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->foreignId('pegawai_id')->constrained('users'); 
            $table->text('catatan');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('catatan_pegawai');
    }
};