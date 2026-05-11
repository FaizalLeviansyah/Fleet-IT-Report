<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengecekan cerdas: Jika kolomnya belum ada, maka buatkan!
        if (!Schema::hasColumn('assets', 'software_list')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->json('software_list')->nullable()->after('disk_space');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('assets', 'software_list')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->dropColumn('software_list');
            });
        }
    }
};
