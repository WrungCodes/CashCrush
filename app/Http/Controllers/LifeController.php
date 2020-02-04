<?php

namespace App\Http\Controllers;

use App\Helpers\Find;
use App\Helpers\Paginate;
use App\Http\Actions\AddLife;
use App\Http\Actions\ReduceLife;
use App\Http\Resources\LeaderBoard;
use App\Life;
use App\Point;
use App\ScoreBoard;
use App\User;
use Illuminate\Http\Request;
use stdClass;

class LifeController extends Controller
{
    public function useLife(Request $request)
    {
        return ['lifes' => (new ReduceLife(Find::findAuthUser($request)))->execute()];
    }

    public function addLife(Request $request)
    {
        return ['lifes' => (new AddLife(Find::findAuthUser($request)))->execute()];
    }

    public function getLife(Request $request)
    {
        return ['lifes' => Find::findAuthUser($request)->life->no_of_lifes];
    }
}
