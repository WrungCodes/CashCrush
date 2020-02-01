<?php

namespace App\Traits;

use App\Item;
use App\User;
use Illuminate\Support\Facades\DB;
use Throwable;

trait InventoryTrait
{

    public function addItems(User $user, array $items)
    {
        $userId = $user->id;

        $array = [];

        foreach ($items as $item) {
            $it = ['user_id' => $userId, 'item_id' => $item->id, 'item_uid' => $item->uid];

            array_push($array, $it);
        }

        DB::table('inventory_items')->insert($array);
    }


    public function useItems(User $user, array $items)
    {
        try {

            foreach ($items as $item) {
                $user->inventoryItems()->where(['item_id' => $item->id])->firstOrFail()->delete();
            }
        } catch (Throwable $th) {
            abort(404, "item not found");
        }


        //TODO
        // $dd = $user->inventoryItems()->where(function ($query) use ($items) {
        //     foreach ($items as $item) {
        //         $query->orWhere("item_uid", $item);
        //     }
        // })->get();
    }
}
