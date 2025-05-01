<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckImplement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'brand',
        'model',
        'license_plate',
        'manufacture_year',
        'capacity',
        'photo',
        'truck_id',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
