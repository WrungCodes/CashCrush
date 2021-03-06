<?php

namespace App;

use App\Profile;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'email_token', 'password_token', 'user_type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, InventoryItem::class)->whereNull('inventory_items.deleted_at');;
    }

    public function life()
    {
        return $this->hasOne(Life::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function coinHistory()
    {
        return $this->hasMany(CoinHistory::class);
    }

    public function nairaHistory()
    {
        return $this->hasMany(NairaHistory::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
