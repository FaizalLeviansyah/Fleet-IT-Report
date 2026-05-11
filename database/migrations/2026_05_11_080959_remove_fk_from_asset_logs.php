<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_logs', function (Blueprint $table) {
            // Kita hapus gembok ketatnya agar error 1452 tidak muncul lagi
            $table->dropForeign(['user_id']);
        });
    }

    public function down(): void
    {
        // Kosongkan saja
    }
};
