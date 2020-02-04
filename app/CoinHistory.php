<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinHistory extends Model
{
    public const DEBIT_TYPE = 'debit';
    public const CREDIT_TYPE = 'credit';

    protected $fillable = [
        'initial_amount', 'final_amount', 'type', 'user_id', 'amount'
    ];

    protected $hidden = [
        'id'
    ];
}
