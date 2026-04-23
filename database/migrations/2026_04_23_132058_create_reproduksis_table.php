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
        Schema::create('reproduksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('users', 'id');
            $table->text('keterangan');
            $table->date('tgl_menstruasi');
            $table->date('tgl_input');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reproduksi');
    }
};
