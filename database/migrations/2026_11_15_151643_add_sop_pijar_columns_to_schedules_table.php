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
        if (!Schema::hasColumn('schedules', 'praktisi_nama')) {
            $table->string('praktisi_nama')->nullable();
        }
        if (!Schema::hasColumn('schedules', 'pertemuan_ke')) {
            $table->unsignedTinyInteger('pertemuan_ke')->default(1); // Maksimal 7 per MK
        }
        if (!Schema::hasColumn('schedules', 'dokumen_bahan_ajar')) {
            $table->string('dokumen_bahan_ajar')->nullable(); // Menyimpan path file PDF/PPT RPS & RPS Mini di server
        }
        if (!Schema::hasColumn('schedules', 'nomor_iks')) {
            $table->string('nomor_iks')->nullable(); // Implementasi Kerja Sama
        }
        if (!Schema::hasColumn('schedules', 'status_presensi_pijar')) {
            $table->string('status_presensi_pijar')->default('Formulir Diberikan');
        }
        if (!Schema::hasColumn('schedules', 'tautan_evaluasi')) {
            $table->string('tautan_evaluasi')->nullable(); // Link Evaluasi Pembelajaran PIJAR
        }
    });
}

public function down(): void
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->dropColumn([
            'praktisi_nama', 'pertemuan_ke', 'tautan_gdrive_ajar',
            'nomor_iks', 'status_presensi_pijar', 'tautan_evaluasi'
        ]);
    });
}
};
