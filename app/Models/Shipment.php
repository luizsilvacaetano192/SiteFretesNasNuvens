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
        'weight',
        'cargo_type',
        'dimensions',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function freight(): HasOne
    {
        return $this->hasOne(Freight::class, 'shipment_id');
    }
}
