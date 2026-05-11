<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('mac_address')->nullable()->after('ip_address');
            $table->string('current_user')->nullable()->after('disk_space');
            $table->dateTime('last_boot_time')->nullable()->after('current_user');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['mac_address', 'current_user', 'last_boot_time']);
        });
    }
};
