<?php

namespace App\Helpers;

use App\Item;
use App\User;

class Find
{
    public static function findUser($key, $value)
    {
        return User::where([$key => $value])->first();
    }

    public static function findAuthUser($request)
    {
        return $request->user;
    }

    public static function GetItemObjectWithUid(string $uid)
    {
        return Item::where(['uid' => $uid])->first();
    }
}
