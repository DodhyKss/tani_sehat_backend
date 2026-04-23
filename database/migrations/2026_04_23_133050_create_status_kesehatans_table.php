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
        Schema::create('status_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('users', 'id');
            $table->integer('status_td')->nullable();
            $table->integer('tekanan_darah')->nullable();
            $table->integer('status_gad')->nullable();
            $table->integer('skor_gad')->nullable();
            $table->date('tgl_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_kesehatan');
    }
};
