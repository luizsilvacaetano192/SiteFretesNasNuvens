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
        return $this->driver_license_front
            ? Storage::disk('s3')->url($this->driver_license_front)
            : null;
    }

    public function getDriverLicenseBackUrlAttribute()
    {
        return $this->driver_license_back
            ? Storage::disk('s3')->url($this->driver_license_back)
            : null;
    }

    public function getFacePhotoUrlAttribute()
    {
        return $this->face_photo
            ? Storage::disk('s3')->url($this->face_photo)
            : null;
    }

    public function getAddressProofUrlAttribute()
    {
        return $this->address_proof
            ? Storage::disk('s3')->url($this->address_proof)
            : null;
    }
}
