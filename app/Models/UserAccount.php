
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $fillable = [
        'driver_id',
        'asaas_identifier',
        'total_balance',
        'blocked_balance',
        'available_balance',
        'last_updated_at',
    ];

    protected $dates = ['last_updated_at'];

    // Relacionamento com o motorista (driver)
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // Relacionamento com as transferências
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
}
