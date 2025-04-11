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
        'driver_license_front_photo',
        'driver_license_back_photo',
        'face_photo',
        'address_proof_photo',
        'status',
        'reason'
    ];

    protected $appends = [
        'driver_license_front_url',
        'driver_license_back_url',
        'face_photo_url',
        'address_proof_url',
    ];

    public function getDriverLicenseFrontUrlAttribute()
    {
        return $this->driver_license_front_photo
            ? Storage::disk('s3')->url($this->driver_license_front_photo)
            : null;
    }

    public function getDriverLicenseBackUrlAttribute()
    {
        return $this->driver_license_back_photo
            ? Storage::disk('s3')->url($this->driver_license_back_photo)
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
        return $this->address_proof_photo
            ? Storage::disk('s3')->url($this->address_proof_photo)
            : null;
    }

    public function userAccount()
    {
        return $this->belongsTo(UserAccount::class,'id_driver', 'id');
        
        // ou mantém o belongsTo se a chave estrangeira estiver na tabela user_accounts
    }


}
