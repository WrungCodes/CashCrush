<?php

namespace App\Http\Controllers;

use App\Helpers\Find;
use App\Helpers\Paginate;
use App\Http\Requests\Point as RequestsPoint;
use App\Point;
use App\ScoreBoard;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PointController extends Controller
{

    public function add(RequestsPoint $request)
    {
        $user = Find::findAuthUser($request);
        Point::create([
            'user_id' => $user->id,
            'score' => $request->score
        ]);

        $user->profile()->update([
            'point' => $newPoints = $user->profile->point + $request->score
        ]);

        return ['message' => 'Successful', 'points' => $newPoints];
    }


    public function get(Request $request)
    {
        return ['points' =>  Find::findAuthUser($request)->points->sum('score')];
    }


    public function getAll(Request $request)
    {
        $allLeaderBoard = Point::with('user')->get()->groupBy('user.username')->map(function ($item, $key) {
            // Carbon::setWeekStartsAt(Carbon::SUNDAY);
            // Carbon::setWeekEndsAt(Carbon::SATURDAY);
            // $userScoreBoard = new ScoreBoard($key, $item->sum('score'));
            $userScoreBoard = new ScoreBoard($key, $item->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('score'));

            return  $userScoreBoard;
        })->sortByDesc('score');

        $authUser = Find::findAuthUser($request);

        $authUserScoreBoard =  $allLeaderBoard->where('username', '=', $authUser->username)->first();

        $authUserScoreBoard->position = array_search($authUser->username, array_keys($allLeaderBoard->toArray())) + 1;

        return [
            'user' => $authUserScoreBoard,
            'leaderboard' => Paginate::paginate($allLeaderBoard, 10)
        ];
    }

    public function getAllAdmin(Request $request)
    {
        $allLeaderBoard = Point::with('user')->get()->groupBy('user.username')->map(function ($item, $key) {

            // $userScoreBoard = new ScoreBoard($key, $item->sum('score'));
            $userScoreBoard = new ScoreBoard($key, $item->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('score'));

            return  $userScoreBoard;
        })->sortByDesc('score');

        return [
            Paginate::paginate($allLeaderBoard, 10)
        ];
    }
}
