<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreightDriver extends Model
{
    use HasFactory;

    protected $fillable = [
      
    ];

    public function freight(){

        return $this->hasOne(freight::class);
    }

    public function driver(){

        return $this->hasOne(driver::class);
    }

    public function truck(){

        return $this->hasOne(truck::class);
    }


        
}
