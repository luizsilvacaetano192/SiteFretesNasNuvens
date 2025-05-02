<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freight extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'company_id',
        'status_id',
        'freight_description',
        'loading_instructions',
        'unloading_instructions',
        'start_address',
        'destination_address',
        'pickup_date',
        'delivery_date',
        'truck_type',
        'freight_value',
        'driver_freight_value',
        'distance_value',
        'duration_value',
        'distance_km',
        'distance',
        'duration',
        'duration_min',
        'current_position',
        'current_lat',
        'current_lng',
        'start_lat',
        'start_lng',
        'destination_lat',
        'destination_lng',
        'insurance_carriers'
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime',
        'insurance_carriers' => 'array',
    ];

    // Relacionamento com Shipment
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Relacionamento com Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function freightsDriver()
    {
        return $this->hasOne(FreightsDriver::class)->whereNot('status_id', 10);
    }
    

    // Relacionamento com Statuss
    public function freightStatus()
    {
        return $this->belongsTo(FreightStatus::class,'status_id','id');
    }
    public function charge()
    {
        return $this->hasOne(charge::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    // Métodos auxiliares
    public function getFormattedFreightValueAttribute()
    {
        return 'R$ ' . number_format($this->freight_value, 2, ',', '.');
    }

    public function getFormattedDriverFreightValueAttribute()
    {
        return 'R$ ' . number_format($this->driver_freight_value, 2, ',', '.');
    }

    public function getFormattedPickupDateAttribute()
    {
        return $this->pickup_date->format('d/m/Y H:i');
    }

    public function getFormattedDeliveryDateAttribute()
    {
        return $this->delivery_date->format('d/m/Y H:i');
    }

    public function getTruckTypeNameAttribute()
    {
        $types = [
            'pequeno' => 'Pequeno (até 3 ton)',
            'medio' => 'Médio (3-8 ton)',
            'grande' => 'Grande (8+ ton)',
            'refrigerado_pequeno' => 'Refrigerado Pequeno',
            'refrigerado_medio' => 'Refrigerado Médio',
            'refrigerado_grande' => 'Refrigerado Grande',
            'tanque_pequeno' => 'Tanque Pequeno',
            'tanque_grande' => 'Tanque Grande'
        ];

        return $types[$this->truck_type] ?? $this->truck_type;
    }
}