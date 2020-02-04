<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoinDeal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'price', 'quantity', 'uid', 'is_active', 'description'
    ];

    protected $hidden = [
        'id'
    ];
}
