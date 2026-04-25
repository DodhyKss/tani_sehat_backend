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
        Schema::create('jadwal_pengisian', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['hours', 'day', 'week', 'month', 'year']);
            $table->integer('jumlah');
            $table->enum('jenis_pengisian', ['td', 'gad7']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengisian');
    }
};
