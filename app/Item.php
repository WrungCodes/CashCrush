<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'price', 'description', 'is_active', 'uid', 'type', 'slug'
    ];

    protected $hidden = [
        'id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
