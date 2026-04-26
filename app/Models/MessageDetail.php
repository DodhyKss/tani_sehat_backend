<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageDetail extends Model
{
    protected $table = 'message_details';

    protected $fillable = [
        'message_id',
        'sender_id',
        'message',
        'is_read',
    ];

    public function messageThread()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
