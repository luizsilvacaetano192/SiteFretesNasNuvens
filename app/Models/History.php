<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'address',
        'latitude',
        'longitude',
        'freight_id',
        'status',
    ];

    public function freight()
    {
        return $this->belongsTo(Freight::class);
    }
}
