<?php

namespace App\Http\Actions\WalletActions;

use App\Helpers\Find;
use App\Traits\InventoryTrait;
use App\Traits\WalletTrait;
use App\User;
use Illuminate\Support\Facades\DB;

class UseItem
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
        return $this->processUse();
    }

    public function processUse()
    {
        DB::beginTransaction();

        $items = $this->getItemsWithUid();

        $this->useItems($this->user, $items);

        DB::commit();
    }

    private function getItemsWithUid()
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
