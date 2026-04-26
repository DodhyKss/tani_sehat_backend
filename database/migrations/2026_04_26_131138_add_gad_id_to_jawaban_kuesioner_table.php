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
        Schema::table('jawaban_kuesioner', function (Blueprint $table) {
            $table->foreignId('gad_id')->nullable()->after('warga_id')->constrained('gad', 'id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban_kuesioner', function (Blueprint $table) {
            $table->dropForeign(['gad_id']);
            $table->dropColumn('gad_id');
        });
    }
};
