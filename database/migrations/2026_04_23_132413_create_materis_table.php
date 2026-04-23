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
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('file_path');
            $table->enum('kategori_gad', ['normal', 'ringan', 'sedang', 'tinggi']);
            $table->enum('kategori_td', ['hipertensi', 'pre_hipertensi', 'normal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
