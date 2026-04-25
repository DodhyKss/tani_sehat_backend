<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $table = 'warga';

    protected $fillable = [
        'warga_id',
        'kader_id',
    ];

    public function warga()
    {
        return $this->belongsTo(User::class, 'warga_id');
    }

    public function kader()
    {
        return $this->belongsTo(User::class, 'kader_id');
    }
}
