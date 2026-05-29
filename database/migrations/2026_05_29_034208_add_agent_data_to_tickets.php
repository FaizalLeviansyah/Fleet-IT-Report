<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Wadah untuk data hasil pelacakan Agent
            $table->string('company_name')->nullable()->after('type'); // Misal: PT CTP
            $table->string('location_name')->nullable()->after('company_name'); // Misal: Lantai 4
            $table->string('asset_hostname')->nullable()->after('location_name'); // Misal: DESKTOP-LV123
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'location_name', 'asset_hostname']);
        });
    }
};
