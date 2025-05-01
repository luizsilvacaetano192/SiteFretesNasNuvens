<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

        // In your Truck model
    protected $appends = [
        'front_photo_url',
        'rear_photo_url',
        'left_side_photo_url',
        'right_side_photo_url',
        'crv_photo_url',
        'crlv_photo_url'
    ];

    public function getFrontPhotoFullUrlAttribute()
    {
        return $this->front_photo_url ? Storage::disk('s3')->url($this->front_photo_url) : null;
    }

    public function getRearPhotoFullUrlAttribute()
    {
        return $this->rear_photo_url ? Storage::disk('s3')->url($this->rear_photo_url) : null;
    }

    public function getLeftSidePhotoFullUrlAttribute()
    {
        return $this->left_side_photo_url ? Storage::disk('s3')->url($this->left_side_photo_url) : null;
    }

    public function getRightSidePhotoFullUrlAttribute()
    {
        return $this->right_side_photo_url ? Storage::disk('s3')->url($this->right_side_photo_url) : null;
    }

    public function getCrvPhotoFullUrlAttribute()
    {
        return $this->crv_photo_url ? Storage::disk('s3')->url($this->crv_photo_url) : null;
    }

    public function getCrlvPhotoFullUrlAttribute()
    {
        return $this->crlv_photo_url ? Storage::disk('s3')->url($this->crlv_photo_url) : null;
    }

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