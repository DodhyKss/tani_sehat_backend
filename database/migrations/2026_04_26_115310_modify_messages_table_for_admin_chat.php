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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('kader_id')->nullable()->change();
            $table->foreignId('warga_id')->nullable()->change();
            $table->foreignId('admin_id')->nullable()->after('warga_id')->constrained('users', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
            $table->foreignId('kader_id')->nullable(false)->change();
            $table->foreignId('warga_id')->nullable(false)->change();
        });
    }
};
