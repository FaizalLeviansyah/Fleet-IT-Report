<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Identifier unik dari mesin fisik (Hardware UUID / MAC Address)
            $table->string('hardware_uuid')->unique()->nullable()->after('asset_name');

            // Spesifikasi Hardware (Otomatis terisi dari Agent)
            $table->string('os_version')->nullable();
            $table->string('cpu_model')->nullable();
            $table->string('total_ram')->nullable();
            $table->string('disk_space')->nullable();

            // Fitur Live Monitor (Kapan terakhir kali perangkat ini online?)
            $table->timestamp('last_seen')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['hardware_uuid', 'os_version', 'cpu_model', 'total_ram', 'disk_space', 'last_seen']);
        });
    }
};
