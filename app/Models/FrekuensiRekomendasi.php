<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrekuensiRekomendasi extends Model
{
    protected $table = 'frekuensi_rekomendasi';

    protected $fillable = [
        'user_id',
        'jenis_rekomendasi',
        'tanggal_lihat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
