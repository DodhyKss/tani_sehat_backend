<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    protected $table = 'tindak_lanjut';

    protected $fillable = [
        'user_id',
        'tindak_lanjut_id',
        'tanggal_tindak_lanjut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masterTindakLanjut()
    {
        return $this->belongsTo(MasterTindakLanjut::class, 'tindak_lanjut_id');
    }
}
