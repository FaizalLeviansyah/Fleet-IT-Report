<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            // Menambahkan kolom asset_id setelah kolom vessel_name
            $table->unsignedBigInteger('asset_id')->nullable()->after('vessel_name');
        });
    }

    public function down(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn('asset_id');
        });
    }
};
