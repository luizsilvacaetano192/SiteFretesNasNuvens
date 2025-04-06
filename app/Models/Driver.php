<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'address',
        'identity_card',
        'phone',
        'birth_date',
        'marital_status',
        'cpf',
        'driver_license_number',
        'driver_license_category',
        'driver_license_expiration',
        'password',
        'terms_accepted',
        'driver_license_front',
        'driver_license_back',
        'face_photo',
        'address_proof',
    ];

    public function getDriverLicenseFrontUrlAttribute()
    {
        return Storage::disk('s3')->url($this->driver_license_front);
    }

    public function getDriverLicenseBackUrlAttribute()
    {
        return Storage::disk('s3')->url($this->driver_license_back);
    }

    public function getFacePhotoUrlAttribute()
    {
        return Storage::disk('s3')->url($this->face_photo);
    }

    public function getAddressProofUrlAttribute()
    {
        return Storage::disk('s3')->url($this->address_proof);
    }
}
