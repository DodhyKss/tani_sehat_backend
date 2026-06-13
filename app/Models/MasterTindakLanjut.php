<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTindakLanjut extends Model
{
    protected $table = 'master_tindak_lanjut';

    protected $fillable = [
        'nama_tindakan',
        'jenis_tindakan',
        'kategori',
    ];
}
