<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Life extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_of_lifes', 'user_id'
    ];

    protected $hidden = [
        'id'
    ];

    protected $casts = [
        'lives' => 'int',
    ];
}
