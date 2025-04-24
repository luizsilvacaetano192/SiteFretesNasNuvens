<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreightStatus extends Model
{
    use HasFactory;

    protected $fillable = [
      'name'
    ];

    public function freight()
    {
        return $this->belongsTo(freight::class);
    }
        
}
