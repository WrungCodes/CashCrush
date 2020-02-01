<?php

namespace App\Http\Actions\WalletActions;

use App\Helpers\Find;
use App\Traits\InventoryTrait;
use App\Traits\WalletTrait;
use App\User;
use Illuminate\Support\Facades\DB;

class BuyItem
{
    use WalletTrait;

    use InventoryTrait;

    protected $user;

    protected $itemsUid;

    // private  $itemsArray = [];

    public function __construct(User $user, array $itemsUid)
    {
        $this->user = $user;
        $this->itemsUid = $itemsUid;
    }

    public function execute()
    {
        return $this->processBuy();
    }

    public function processBuy()
    {
        DB::beginTransaction();

        $data = $this->getTotalPriceOfItemsAndItems();

        $price = $data['price'];

        $currentCoinBalance = $this->DebitCoin($this->user, $price);

        $items = $data['items'];

        $this->addItems($this->user, $items);

        DB::commit();

        return $currentCoinBalance;
    }

    private function getTotalPriceOfItemsAndItems()
    {
        $totalPrice = 0;

        $itemsArray = [];

        foreach ($this->itemsUid as $uid) {

            $item = Find::GetItemObjectWithUid($uid);

            if (!$item) {
                abort(400, "invalid item");
            }

            array_push($itemsArray, $item);

            $totalPrice += $item->price;
        }

        return ['items' => $itemsArray, 'price' => $totalPrice];
    }
}
