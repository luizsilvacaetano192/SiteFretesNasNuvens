<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'driver_id',
        'weight',
        'cargo_type',
        'dimensions',
        'volume',
        'truck_type',
        'start_address',
        'destination_address',
        'expected_start_date',
        'expected_delivery_date',
        'deadline',
        'start_time',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function freight(): HasOne
    {
        return $this->hasOne(Freight::class, 'shipment_id');
    }
}
