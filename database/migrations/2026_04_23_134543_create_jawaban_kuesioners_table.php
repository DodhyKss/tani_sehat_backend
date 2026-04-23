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
        Schema::create('jawaban_kuesioner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioner', 'id');
            $table->foreignId('warga_id')->constrained('users', 'id');
            $table->integer('skor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_kuesioner');
    }
};
