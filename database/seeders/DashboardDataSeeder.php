<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class DashboardDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Ruang Kuliah
        $smartClass = Room::updateOrCreate(['kode_ruang' => 'Smart Class'], [
            'nama_ruang' => 'Smart Class Gedung Utama',
            'kapasitas' => 45,
            'is_available' => true,
        ]);

        $roomA221 = Room::updateOrCreate(['kode_ruang' => 'A221'], [
            'nama_ruang' => 'Ruang Kelas A221',
            'kapasitas' => 40,
            'is_available' => false,
        ]);

        // 2. Data Mata Kuliah sesuai foto
        $mk1 = Course::updateOrCreate(['kode_mk' => 'IS30212'], [
            'nama_mk' => 'Algoritma & Pemecahan Masalah',
            'sks' => 2,
            'prodi' => 'Sistem Informasi',
        ]);

        $mk2 = Course::updateOrCreate(['kode_mk' => 'IS30513'], [
            'nama_mk' => 'Teori Sistem Perkotaan',
            'sks' => 3,
            'prodi' => 'Sistem Informasi',
        ]);

        $mk3 = Course::updateOrCreate(['kode_mk' => 'IS30613'], [
            'nama_mk' => 'Sistem Operasi',
            'sks' => 3,
            'prodi' => 'Sistem Informasi',
        ]);

        $mk4 = Course::updateOrCreate(['kode_mk' => 'IS30413'], [
            'nama_mk' => 'Infrastruktur TIK',
            'sks' => 3,
            'prodi' => 'Sistem Informasi',
        ]);

        $kaprodi = User::where('role', 'kaprodi')->first() ?? User::first();

        // 3. Data Pengajuan Jadwal Terbaru sesuai tabel di foto
        $schedules = [
            [
                'course_id' => $mk1->id,
                'room_id' => $smartClass->id,
                'submitted_by' => $kaprodi->id,
                'dosen_pengajar' => 'Wahyu Tisno Atmojo, S.Kom., M.Kom',
                'peran_pengaju' => 'Kaprodi SI',
                'tanggal_pengajuan' => '2026-03-24 10:30:00',
                'status' => 'pending_approval',
                'is_external' => false,
            ],
            [
                'course_id' => $mk2->id,
                'room_id' => $smartClass->id,
                'submitted_by' => $kaprodi->id,
                'dosen_pengajar' => "Erick Dazki, S.Kom., M.Kom.\nDeasy Olivia, S.T., M.T.",
                'peran_pengaju' => 'Dosen SI',
                'tanggal_pengajuan' => '2026-03-23 12:30:00',
                'status' => 'approved',
                'is_external' => false,
            ],
            [
                'course_id' => $mk3->id,
                'room_id' => $roomA221->id,
                'submitted_by' => $kaprodi->id,
                'dosen_pengajar' => 'Belsana Butar Butar, S.Kom., M.Kom.',
                'peran_pengaju' => 'Dosen SI',
                'tanggal_pengajuan' => '2026-03-23 14:45:00',
                'status' => 'revision_requested',
                'is_external' => false,
            ],
            [
                'course_id' => $mk4->id,
                'room_id' => $smartClass->id,
                'submitted_by' => $kaprodi->id,
                'dosen_pengajar' => 'Hendra Mayatopani, S.T., M.M.SI.',
                'peran_pengaju' => 'Dosen SI',
                'tanggal_pengajuan' => '2026-03-21 09:15:00',
                'status' => 'pending_approval',
                'is_external' => false,
            ],
        ];

        foreach ($schedules as $item) {
            Schedule::create($item);
        }
    }
}