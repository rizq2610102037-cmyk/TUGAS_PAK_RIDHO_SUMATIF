<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('kode_mk')->unique(); // Contoh: IS30212, IS30513
        $table->string('nama_mk');           // Contoh: Algoritma & Pemecahan Masalah
        $table->integer('sks');
        $table->string('prodi')->default('Sistem Informasi');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
