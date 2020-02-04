<?php

namespace App\Http\Controllers;

use App\CoinDeal;
use App\Helpers\Find;
use App\Http\Actions\WalletActions\BuyCoin;
use App\Http\Requests\CoinDeal as RequestCoinDeal;
use App\Http\Resources\CoinDeal as ResourcesCoinDeal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CoinDealController extends Controller
{
    public function buy(Request $request)
    {
        return (new BuyCoin(Find::findAuthUser($request),  $request->uid))->execute();
    }

    public function get(Request $request)
    {
        return ResourcesCoinDeal::collection(CoinDeal::all());
    }

    public function create(RequestCoinDeal $request)
    {
        return ['coindeal' => new ResourcesCoinDeal(CoinDeal::create([
            'name' => $name = $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'uid' => (string) Str::random(10),
            'is_active' => 0,
            'slug' => Str::slug($name, '_')
        ]))];
    }
}
