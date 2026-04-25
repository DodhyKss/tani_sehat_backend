<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPengisian extends Model
{
    protected $table = 'jadwal_pengisian';

    protected $fillable = [
        'tipe',
        'jumlah',
        'jenis_pengisian',
    ];
}
