<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'driver_id',
        'license_plate',
        'brand',
        'model',
        'manufacture_year',
        'renavam',
        'chassis_number',
        'crv_number',
        'crlv_number',
        'vehicle_type',
        'load_capacity',
        'axles_number',
        'tare',
        'gross_weight',
        'body_type',
        'body_material',
        'dimensions',
        'front_photo_url',
        'rear_photo_url',
        'left_side_photo_url',
        'right_side_photo_url',
        'crv_photo_url',
        'crlv_photo_url',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'manufacture_year' => 'integer',
        'load_capacity' => 'decimal:2',
        'tare' => 'decimal:2',
        'gross_weight' => 'decimal:2'
    ];

    /**
     * Relacionamento com o motorista
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function implements()
    {
        return $this->hasMany(TruckImplement::class);
    }

}