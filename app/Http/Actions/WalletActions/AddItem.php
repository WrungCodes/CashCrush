<?php

namespace App\Http\Actions\WalletActions;

use App\Helpers\Find;
use App\Traits\InventoryTrait;
use App\Traits\WalletTrait;
use App\User;
use Illuminate\Support\Facades\DB;

class AddItem
{

    use InventoryTrait;

    protected $user;

    protected $itemsUid;


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

        $items = $this->getTotalItems();

        $this->addItems($this->user, $items);

        DB::commit();
    }

    private function getTotalItems()
    {
        $itemsArray = [];

        foreach ($this->itemsUid as $uid) {

            $item = Find::GetItemObjectWithUid($uid);

            if (!$item) {
                abort(400, "invalid item");
            }

            array_push($itemsArray, $item);
        }

        return $itemsArray;
    }
}
