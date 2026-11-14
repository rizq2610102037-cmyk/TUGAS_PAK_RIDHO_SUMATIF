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
    Schema::table('schedules', function (Blueprint $table) {
        if (!Schema::hasColumn('schedules', 'mitra_instansi')) {
            $table->string('mitra_instansi')->nullable();
        }
        if (!Schema::hasColumn('schedules', 'jabatan_praktisi')) {
            $table->string('jabatan_praktisi')->nullable();
        }
        if (!Schema::hasColumn('schedules', 'status_eksternal')) {
            $table->string('status_eksternal')->default('ready_to_teach');
        }
        if (!Schema::hasColumn('schedules', 'status_honorarium')) {
            $table->string('status_honorarium')->default('Tersedia (SBU 2026)');
        }
        if (!Schema::hasColumn('schedules', 'status_bap')) {
            $table->string('status_bap')->default('Draft Otomatis Siap');
        }
        if (!Schema::hasColumn('schedules', 'pic_akademik')) {
            $table->string('pic_akademik')->nullable();
        }
        if (!Schema::hasColumn('schedules', 'pic_kontak')) {
            $table->string('pic_kontak')->nullable();
        }
        if (!Schema::hasColumn('schedules', 'tautan_zoom')) {
            $table->string('tautan_zoom')->nullable();
        }
    });
}
};
