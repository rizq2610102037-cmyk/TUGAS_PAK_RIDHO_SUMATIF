<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('practitioners', function (Blueprint $table) {
        if (!Schema::hasColumn('practitioners', 'nama')) {
            $table->string('nama');
        }
        if (!Schema::hasColumn('practitioners', 'mitra_instansi')) {
            $table->string('mitra_instansi');
        }
        if (!Schema::hasColumn('practitioners', 'jabatan')) {
            $table->string('jabatan');
        }
        if (!Schema::hasColumn('practitioners', 'bidang_keahlian')) {
            $table->string('bidang_keahlian');
        }
        if (!Schema::hasColumn('practitioners', 'pengalaman')) {
            $table->text('pengalaman')->nullable();
        }
        if (!Schema::hasColumn('practitioners', 'kontak')) {
            $table->string('kontak')->nullable();
        }
        if (!Schema::hasColumn('practitioners', 'is_active')) {
            $table->boolean('is_active')->default(true);
        }
    });
}
};
