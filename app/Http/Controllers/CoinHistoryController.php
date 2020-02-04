<?php

namespace App\Http\Controllers;

use App\CoinHistory;
use App\Helpers\Find;
use App\Http\Resources\WalletHistory;
use Illuminate\Http\Request;

class CoinHistoryController extends Controller
{

    public function getUser(Request $request)
    {
        return ['history' => WalletHistory::collection(Find::findAuthUser($request)->coinHistory)];
    }

    public function getAll(Request $request)
    {
        return  ['history' => WalletHistory::collection(CoinHistory::all())];
    }
}
