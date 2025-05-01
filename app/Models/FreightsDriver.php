<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreightsDriver extends Model
{
    use HasFactory;

    protected $fillable = [
      
    ];

    public function freight(){

        return $this->belongsTo(Freight::class);
    }

    public function driver(){

        return $this->belongsTo(Driver::class);
    }

    public function truck(){

        return $this->belongsTo(Truck::class);
    }
        
}
