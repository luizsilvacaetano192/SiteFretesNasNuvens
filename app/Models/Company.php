<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'trading_name',
        'cnpj',
        'state_registration',
        'phone',
        'whatsapp',
        'email',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'description',
        'website',
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // Adicione aqui campos sensíveis que não devem ser expostos
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Prepare CNPJ for validation/saving (remove formatting)
     */
    public function setCnpjAttribute($value)
    {
        $this->attributes['cnpj'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Format CNPJ for display
     */
    public function getFormattedCnpjAttribute()
    {
        $cnpj = $this->attributes['cnpj'];
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . 
               substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . 
               substr($cnpj, 12, 2);
    }

    /**
     * Prepare phone numbers for saving (remove formatting)
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setWhatsappAttribute($value)
    {
        $this->attributes['whatsapp'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    /**
     * Format phone numbers for display
     */
    public function getFormattedPhoneAttribute()
    {
        $phone = $this->attributes['phone'];
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6, 4);
    }

    public function getFormattedWhatsappAttribute()
    {
        if (empty($this->attributes['whatsapp'])) {
            return null;
        }
        
        $whatsapp = $this->attributes['whatsapp'];
        return '(' . substr($whatsapp, 0, 2) . ') ' . 
               substr($whatsapp, 2, 5) . '-' . 
               substr($whatsapp, 7, 4);
    }

    /**
     * Prepare zip code for saving (remove formatting)
     */
    public function setZipCodeAttribute($value)
    {
        $this->attributes['zip_code'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Format zip code for display
     */
    public function getFormattedZipCodeAttribute()
    {
        $zipCode = $this->attributes['zip_code'];
        return substr($zipCode, 0, 5) . '-' . substr($zipCode, 5, 3);
    }

    /**
     * Get complete address
     */
    public function getFullAddressAttribute()
    {
        return trim(implode(', ', array_filter([
            $this->address,
            $this->number,
            $this->complement,
            $this->neighborhood,
            $this->city,
            $this->state,
            $this->formatted_zip_code
        ])));
    }

    /**
     * Scope for active companies
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}