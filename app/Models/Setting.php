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
        'large_truck_price',
        'small_refrigerated_rate',
        'medium_refrigerated_rate',
        'large_refrigerated_rate',
        'small_tanker_rate',
        'medium_tanker_rate',
        'large_tanker_rate',
        'minimum_freight_value',
        'weight_surcharge_3000',
        'weight_surcharge_5000',
        'fragile_surcharge',
        'hazardous_surcharge'
    ];
}