<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('judul_laporan');
            $table->string('nama_pegawai');
            $table->text('deskripsi_pekerjaan');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pegawais');
    }
};
