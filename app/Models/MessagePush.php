<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessagePush extends Model
{
    protected $table = 'messages_push';

    protected $fillable = [
        'driver_id',
        'texto',
        'token',
        'link',
        'data',
        'send',
        'erro',
        'type',
        'titulo',
        'screen'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
