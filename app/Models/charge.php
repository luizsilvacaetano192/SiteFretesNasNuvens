<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = [
        'freight_id',
        'amount',
        'payment_method',
        'asaas_charge_id',
        'asaas_payment_id',
        'status',
        'charge_url',
        'receipt_url',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    // ENUM status helpers
    public const STATUS_UNPAID = 'unpaid';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELED = 'canceled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_UNPAID,
            self::STATUS_PAID,
            self::STATUS_CANCELED,
        ];
    }

    // Relationships
    public function freight()
    {
        return $this->belongsTo(Freight::class);
    }
}
