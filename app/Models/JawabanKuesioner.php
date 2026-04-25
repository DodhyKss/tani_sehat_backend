<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanKuesioner extends Model
{
    protected $table = 'jawaban_kuesioner';

    protected $fillable = [
        'kuesioner_id',
        'warga_id',
        'skor',
    ];

    public function kuesioner()
    {
        return $this->belongsTo(Kuesioner::class, 'kuesioner_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'warga_id');
    }
}
