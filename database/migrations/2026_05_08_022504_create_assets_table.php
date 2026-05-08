<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained('vessels')->onDelete('cascade');
            $table->string('asset_type'); // Kategori: Hardware, Network, Software, dll
            $table->string('asset_name'); // Misal: Router Mikrotik RB750
            $table->string('ip_address')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('status')->default('Active'); // Active, Maintenance, Broken
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
