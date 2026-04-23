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
        Schema::create('tekanan_darah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('users', 'id');
            $table->integer('systolic');
            $table->integer('diastolic');
            $table->date('tgl_cek');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tekanan_darah');
    }
};
