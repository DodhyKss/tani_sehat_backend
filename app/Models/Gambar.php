<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    protected $table = 'gambar';

    protected $fillable = [
        'judul',
        'file_path',
        'kategori_gad',
        'kategori_td',
    ];
}
