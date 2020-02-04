<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public const TRANSACTION_PENDING = 'pending';

    public const TRANSACTION_FAILED = 'failed';

    public const TRANSACTION_SUCCESSFUL = 'successful';

    public const TRANSACTION_TYPE_COIN = 'coin';

    public const TRANSACTION_TYPE_ITEM = 'item';

    public const TRANSACTION_TYPE_COMBO = 'combo';

    protected $fillable = [
        'amount', 'user_id', 'platform', 'transaction_type', 'transaction_type_uid', 'transaction_ref', 'access_code', 'status'
    ];

    protected $hidden = [
        'id'
    ];
}
