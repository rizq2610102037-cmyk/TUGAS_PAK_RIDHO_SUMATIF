<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
    'course_id', 'room_id', 'submitted_by', 'dosen_pengajar',
    'praktisi_nama', 'mitra_instansi', 'jabatan_praktisi', 'peran_pengaju',
    'kelas', 'pertemuan_ke', 'hari_sesi', 'waktu_sesi',
    'catatan_pengaju', 'catatan_admin', 'dokumen_bahan_ajar',
    'nomor_iks', 'status_presensi_pijar', 'tautan_evaluasi',
    'tanggal_pengajuan', 'status', 'status_eksternal',
    'status_honorarium', 'status_bap', 'pic_akademik', 'pic_kontak',
    'tautan_zoom', 'is_external'
];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'is_external' => 'boolean',
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