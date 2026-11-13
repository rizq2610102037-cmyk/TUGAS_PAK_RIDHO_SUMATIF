<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['kode_mk', 'nama_mk', 'sks', 'prodi'];
}