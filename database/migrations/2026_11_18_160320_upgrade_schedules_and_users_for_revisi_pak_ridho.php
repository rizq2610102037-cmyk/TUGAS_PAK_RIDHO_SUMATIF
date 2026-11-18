<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pembaruan Tabel Schedules (Logika Semester, PIJAR vs SUAR, Pra & Pasca Pelaksanaan)
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'jenis_program')) {
                $table->enum('jenis_program', ['PIJAR', 'SUAR'])->default('PIJAR'); // Pembeda PIJAR vs SUAR
            }
            if (!Schema::hasColumn('schedules', 'tahun_ajaran')) {
                $table->string('tahun_ajaran')->default('2026-2027'); // 2026-2027 s.d. 2030-2031
            }
            if (!Schema::hasColumn('schedules', 'semester_jenis')) {
                $table->enum('semester_jenis', ['Ganjil', 'Genap'])->default('Ganjil');
            }
            if (!Schema::hasColumn('schedules', 'semester_angka')) {
                $table->unsignedTinyInteger('semester_angka')->default(1); // Semester 1 - 8
            }
            if (!Schema::hasColumn('schedules', 'dosen_nidn')) {
                $table->string('dosen_nidn')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'dosen_email_kampus')) {
                $table->string('dosen_email_kampus')->nullable();
            }
            // Kategori Unggahan Pra-Pelaksanaan (Disimpan langsung di sistem)
            if (!Schema::hasColumn('schedules', 'dokumen_rps')) {
                $table->string('dokumen_rps')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'dokumen_silabus')) {
                $table->string('dokumen_silabus')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'link_modul_ajar')) {
                $table->text('link_modul_ajar')->nullable(); // Tautan Modul 1 - 13
            }
            // Kategori Pelaporan Pasca-Pelaksanaan (Terpisah dari ACC Usulan)
            if (!Schema::hasColumn('schedules', 'jumlah_pertemuan_terlaksana')) {
                $table->unsignedTinyInteger('jumlah_pertemuan_terlaksana')->default(0); // Target 1-12 pertemuan
            }
            if (!Schema::hasColumn('schedules', 'dokumen_absensi')) {
                $table->string('dokumen_absensi')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'evaluasi_pembelajaran')) {
                $table->text('evaluasi_pembelajaran')->nullable(); // Diisi langsung di sistem
            }
            if (!Schema::hasColumn('schedules', 'link_gdrive_dokumentasi')) {
                $table->string('link_gdrive_dokumentasi')->nullable(); // Link Google Drive banyak foto
            }
            if (!Schema::hasColumn('schedules', 'status_pelaksanaan')) {
                $table->enum('status_pelaksanaan', ['belum_mulai', 'berlangsung', 'selesai'])->default('belum_mulai');
            }
            if (!Schema::hasColumn('schedules', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable();
            }
        });

        // 2. Pembaruan Tabel Users (NIDN, Prodi, Wajib Reset Password Awal)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nidn')) {
                $table->string('nidn')->nullable();
            }
            if (!Schema::hasColumn('users', 'prodi')) {
                $table->string('prodi')->nullable();
            }
            if (!Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false);
            }
        });

        // 3. Pembaruan Tabel Courses (Semester Kurikulum)
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'semester')) {
                $table->unsignedTinyInteger('semester')->default(1);
            }
        });
    }

    public function down(): void
    {
        //
    }
};