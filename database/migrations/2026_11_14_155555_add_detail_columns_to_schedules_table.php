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
        $table->string('kelas')->default('Kelas A')->after('peran_pengaju');
        $table->string('hari_sesi')->default('Rabu, 26 Maret 2026')->after('kelas');
        $table->string('waktu_sesi')->default('08:00 - 10:30 WIB')->after('hari_sesi');
        $table->text('catatan_pengaju')->nullable()->after('waktu_sesi');
        $table->text('catatan_admin')->nullable()->after('catatan_pengaju');
    });
}

public function down(): void
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->dropColumn(['kelas', 'hari_sesi', 'waktu_sesi', 'catatan_pengaju', 'catatan_admin']);
    });
}
};
