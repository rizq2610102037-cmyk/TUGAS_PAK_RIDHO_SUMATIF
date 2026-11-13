<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'course_id', 'room_id', 'submitted_by', 'dosen_pengajar',
        'peran_pengaju', 'tanggal_pengajuan', 'status', 'is_external'
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