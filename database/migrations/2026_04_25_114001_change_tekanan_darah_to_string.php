<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE status_kesehatan MODIFY COLUMN tekanan_darah VARCHAR(20)");
    }

    public function down(): void
    {
        Schema::table('status_kesehatan', function (Blueprint $table) {
            $table->integer('tekanan_darah')->change();
        });
    }
};