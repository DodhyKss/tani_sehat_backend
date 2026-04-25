<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reproduksi extends Model
{
    protected $table = 'reproduksi';

    protected $fillable = [
        'warga_id',
        'keterangan',
        'tgl_menstruasi',
        'tgl_input',
    ];

    protected $casts = [
        'tgl_menstruasi' => 'date',
        'tgl_input' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'warga_id');
    }
}
