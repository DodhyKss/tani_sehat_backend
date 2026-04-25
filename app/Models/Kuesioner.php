<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuesioner extends Model
{
    protected $table = 'kuesioner';

    protected $fillable = [
        'soal',
    ];

    public function jawaban()
    {
        return $this->hasMany(JawabanKuesioner::class, 'kuesioner_id');
    }
}
