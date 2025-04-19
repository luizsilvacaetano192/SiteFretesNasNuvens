<?php

// app/Models/PendingTask.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingTask extends Model
{
    use HasFactory;

    protected $table = 'pending_tasks';

    protected $fillable = [
        'message',
        'scheduled_at',
        'seen',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'seen' => 'boolean',
    ];
}
