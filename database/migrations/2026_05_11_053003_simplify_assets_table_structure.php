<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus tabel asset_software yang bikin pusing
        Schema::dropIfExists('asset_software');

        // 2. Tambahkan 1 kolom JSON canggih di tabel assets
        Schema::table('assets', function (Blueprint $table) {
            $table->json('software_list')->nullable()->after('disk_space');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('software_list');
        });
    }
};
