<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_account_id',
        'freight_id', // já adicionando para quando fizer o relacionamento
        'type',
        'amount',
        'description',
        'transfer_date',
        'asaas_identifier',
    ];

    public function freight()
    {
        return $this->belongsTo(Freight::class);
    }

    public function userAccount()
    {
        return $this->belongsTo(UserAccount::class);
    }
    
}

