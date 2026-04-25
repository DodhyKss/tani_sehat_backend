<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusKesehatan extends Model
{
    protected $table = 'status_kesehatan';

    protected $fillable = [
        'warga_id',
        'tekanan_darah',
        'skor_gad',
        'kategori_gad',
        'kategori_td',
        'tgl_update',
    ];

    protected $casts = [
        'tgl_update' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'warga_id');
    }
}
