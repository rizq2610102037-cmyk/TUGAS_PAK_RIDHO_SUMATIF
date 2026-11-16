<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practitioner extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'mitra_instansi',
        'jabatan',
        'bidang_keahlian',
        'pengalaman',
        'kontak',
        'is_active',
    ];
}