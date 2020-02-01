<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'item_id', 'item_uid'
    ];

    protected $hidden = [
        'id'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function item()
    {
        return $this->hasOne(Item::class);
    }
}
