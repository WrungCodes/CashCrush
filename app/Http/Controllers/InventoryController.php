<?php

namespace App\Http\Controllers;

use App\Helpers\Find;
use App\Http\Actions\WalletActions\BuyItem;
use App\Http\Actions\WalletActions\UseItem;
use App\Http\Requests\TradeItem;
use App\Http\Resources\Item;
use App\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function buyItem(TradeItem $request)
    {
        return ['message' => 'Successful', 'balance' => (new BuyItem(Find::findAuthUser($request), $request->items))->execute()];
    }

    public function useItem(TradeItem $request)
    {
        (new UseItem(Find::findAuthUser($request), $request->items))->execute();
        return ['message' => 'Successful'];
    }

    public function getInventory(Request $request)
    {
        return Item::collection(Find::findAuthUser($request)->items);
    }
}
