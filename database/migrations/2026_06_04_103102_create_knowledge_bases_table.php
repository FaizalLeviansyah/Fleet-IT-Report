<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Artikel
            $table->string('category')->default('Umum'); // Kategori (Jaringan, Printer, Software, dll)
            $table->longText('content'); // Isi tutorial/SOP
            $table->unsignedBigInteger('author_id'); // Siapa dari Tim IT yang menulis
            $table->boolean('is_public')->default(true); // Jika false, hanya IT yang bisa baca (SOP Internal)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
    }
};
