<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Menghapus paksaan relasi fisik agar tidak terjadi error 1452
            $table->dropForeign(['requester_id']);
            $table->dropForeign(['observer_id']);
            $table->dropForeign(['assigned_to_id']);
        });
    }

    public function down(): void
    {
        // Kosongkan
    }
};
