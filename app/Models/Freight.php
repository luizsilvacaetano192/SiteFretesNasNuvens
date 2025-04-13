<?php

namespace App\Models;

use Aws\S3\Transfer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Freight extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'current_position',
        'status_id',
        'shipment_id',
        'start_address',
        'destination_address',
        'current_lat',
        'current_lng',
        'start_lat',
        'start_lng',
        'destination_lat',
        'destination_lng',
        'company_id',
        'truck_type',
        'status_id',
        'distance',
        'duration' ,
        'directions',
        'freight_value',
        'asaas_payment_id',
        'paymentLink',
        'is_payment_confirmed'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Relacionamento com a tabela companies
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function status()
    {
        return $this->belongsTo(FreightStatus::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
}
