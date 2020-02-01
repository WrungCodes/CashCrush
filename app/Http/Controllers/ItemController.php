<?php

namespace App\Http\Controllers;

use App\Http\Requests\Item as ItemRequest;
use App\Http\Resources\Item as ResourcesItem;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function create(ItemRequest $request)
    {
        $request->validated();

        return ['item' => new ResourcesItem(Item::create([
            'name' => $name = $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'type' => $request->type,
            'uid' => (string) Str::random(10),
            'is_active' => 0,
            'slug' => Str::slug($name, '_')
        ]))];
    }

    public function edit(ItemRequest $request)
    {
        $request->validated();

        $user = Item::where(['uid' => $request->uid])->first();

        $user->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        return new ResourcesItem($user);
    }

    public function get(Request $request)
    {
        return new ResourcesItem((Item::where(['uid' => $request->uid])->first()));
    }

    public function getAll(Request $request)
    {
        return ["items" => ResourcesItem::collection(Item::all())];
    }

    public function delete(Request $request)
    {
        $item = Item::where(['uid' => $request->uid])->first();

        $item->delete();

        return new ResourcesItem($item);
    }
}
