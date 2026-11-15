<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('schedules', function (Blueprint $table) {
        if (!Schema::hasColumn('schedules', 'dokumen_bahan_ajar')) {
            $table->string('dokumen_bahan_ajar')->nullable();
        }
    });
}
};
