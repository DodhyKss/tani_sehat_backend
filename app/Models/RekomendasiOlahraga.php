<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiOlahraga extends Model
{
    protected $table = 'rekomendasi_olahragas';

    protected $fillable = [
        'nama_olahraga',
        'kategori_gad',
        'kategori_td',
    ];
}
