<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_tindak_lanjut', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tindakan');
            $table->enum('jenis_tindakan', ['td', 'gad7']);
            $table->enum('kategori', ['normal', 'pra_hipertensi', 'hipertensi', 'ringan', 'sedang', 'tinggi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tindak_lanjut');
    }
};
