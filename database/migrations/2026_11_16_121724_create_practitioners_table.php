<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('practitioners', function (Blueprint $table) {$table->id();
        $table->string('nama');$table->string('mitra_instansi');
        $table->string('jabatan');$table->string('bidang_keahlian'); // Kompetensi & Keahlian
        $table->text('pengalaman')->nullable(); // Ringkasan pengalaman industri/mengajar$table->string('kontak')->nullable(); // Email / No. HP
        $table->boolean('is_active')->default(true);$table->timestamps();
    });
}
};
