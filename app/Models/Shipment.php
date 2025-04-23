<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'cargo_type',
        'weight',
        'dimensions',
        'volume',
        'description',
        'is_fragile',
        'is_hazardous',
        'requires_temperature_control',
        'min_temperature',
        'max_temperature',
        'temperature_tolerance',
        'temperature_control_type',
        'temperature_unit',
        'temperature_notes'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_fragile' => 'boolean',
        'is_hazardous' => 'boolean',
        'requires_temperature_control' => 'boolean',
        'weight' => 'decimal:2',
        'volume' => 'decimal:2',
        'min_temperature' => 'decimal:2',
        'max_temperature' => 'decimal:2',
        'temperature_tolerance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the company that owns the shipment.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope for fragile shipments.
     */
    public function scopeFragile($query)
    {
        return $query->where('is_fragile', true);
    }

    /**
     * Scope for hazardous shipments.
     */
    public function scopeHazardous($query)
    {
        return $query->where('is_hazardous', true);
    }

    /**
     * Scope for temperature controlled shipments.
     */
    public function scopeTemperatureControlled($query)
    {
        return $query->where('requires_temperature_control', true);
    }

    /**
     * Get formatted dimensions.
     */
    public function getFormattedDimensionsAttribute()
    {
        return str_replace('x', ' × ', $this->dimensions) . ' cm';
    }

    /**
     * Get formatted weight.
     */
    public function getFormattedWeightAttribute()
    {
        return number_format($this->weight, 2) . ' kg';
    }

    public function freight(): HasOne
    {
        return $this->hasOne(Freight::class, 'shipment_id');
    }
}