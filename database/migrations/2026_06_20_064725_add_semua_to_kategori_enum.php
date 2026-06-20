<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['materi', 'video', 'gambar', 'rekomendasi_olahragas'];
        
        foreach ($tables as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `kategori_td` ENUM('hipertensi', 'pre_hipertensi', 'normal', 'semua', 'tidak_salah_satunya') NOT NULL");
            DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `kategori_gad` ENUM('normal', 'ringan', 'sedang', 'tinggi', 'semua', 'tidak_salah_satunya') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['materi', 'video', 'gambar', 'rekomendasi_olahragas'];
        
        foreach ($tables as $table) {
            DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `kategori_td` ENUM('hipertensi', 'pre_hipertensi', 'normal') NOT NULL");
            DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `kategori_gad` ENUM('normal', 'ringan', 'sedang', 'tinggi') NOT NULL");
        }
    }
};
