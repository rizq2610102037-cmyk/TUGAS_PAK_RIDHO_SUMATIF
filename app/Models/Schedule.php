<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'submitted_by',
        'tanggal_pengajuan',
        'course_id',
        'room_id',
        'kelas',
        'dosen_pengajar',
        'dosen_email_kampus',
        'praktisi_nama',
        'praktisi_email',
        'mitra_instansi',
        'jabatan_praktisi',
        'tahun_ajaran',
        'semester_jenis',
        'semester_angka',
        'hari_sesi',
        'waktu_sesi',
        'peran_pengaju',
        'jenis_program',
        'is_external',
        'status',
        'status_eksternal',
        'alasan_penolakan',
        'akun_generated_info',
        'catatan_pengaju',
        'dokumen_bahan_ajar',
        'file_rps',
        'file_silabus',
        'file_modul_1_13',
        'jumlah_pertemuan_terlaksana',
        'status_presensi_pijar',
        'tautan_evaluasi',
        'link_gdrive_dokumentasi',
        'status_bap',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'is_external'       => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}