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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
        $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
        $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
        $table->string('dosen_pengajar'); // Contoh: Wahyu Tisno Atmojo, S.Kom., M.Kom
        $table->string('peran_pengaju');  // Contoh: Kaprodi SI, Dosen SI
        $table->dateTime('tanggal_pengajuan');
        $table->enum('status', ['pending_approval', 'approved', 'revision_requested', 'cancelled'])->default('pending_approval');
        $table->boolean('is_external')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
