<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'cloud_percentage',
        'advance_percentage',
        'small_truck_price',
        'medium_truck_price',
        'large_truck_price'
    ];
}