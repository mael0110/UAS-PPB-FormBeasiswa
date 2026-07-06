<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas_coding', function (Blueprint $table) {
            // Menambahkan kolom kuota setelah nama_kelas
            $table->integer('kuota')->default(0)->after('nama_kelas');
        });
    }

    public function down(): void
    {
        Schema::table('kelas_coding', function (Blueprint $table) {
            $table->dropColumn('kuota');
        });
    }
};