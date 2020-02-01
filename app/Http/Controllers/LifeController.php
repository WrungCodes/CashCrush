<?php

namespace App\Http\Controllers;

use App\Helpers\Find;
use App\Http\Actions\AddLife;
use App\Http\Actions\ReduceLife;
use App\Life;
use Illuminate\Http\Request;

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
