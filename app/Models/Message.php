<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'kader_id',
        'warga_id',
    ];

    public function kader()
    {
        return $this->belongsTo(User::class, 'kader_id');
    }

    public function warga()
    {
        return $this->belongsTo(User::class, 'warga_id');
    }

    public function details()
    {
        return $this->hasMany(MessageDetail::class, 'message_id');
    }

    public function latestDetail()
    {
        return $this->hasOne(MessageDetail::class, 'message_id')->latestOfMany();
    }
}
