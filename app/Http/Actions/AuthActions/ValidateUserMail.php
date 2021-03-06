<?php

namespace App\Http\Actions\AuthActions;

use App\Helpers\Find;
use App\Life;
use App\Profile;
use App\User;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ValidateUserMail
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute(): User
    {
        return $this->validateUserMail();
    }

    public function validateUserMail(): User
    {
        $token = $this->request->token;

        $user = Find::findUser('email_token', $token);

        if (!$user) {
            throw new NotFoundHttpException("Invalid Token");
        }

        if ($user->user_type_id != UserType::PLAYER_USER) {
            abort(401, "Admins can't verify emails");
        }

        DB::BeginTransaction();

        $user->email_token = null;

        $user->save();

        $user->profile()->save($this->createUserProfile(0.00, 0, 0));

        $user->life()->save($this->createUserLife());

        DB::commit();

        return $user;
    }

    private function createUserProfile(float $naira_balance, int $coin_balance, int $points): Profile
    {
        $profile = new Profile;

        $profile->naira_balance = $naira_balance;
        $profile->coin_balance = $coin_balance;
        $profile->point = $points;

        return $profile;
    }

    private function createUserLife(): Life
    {
        $life = new Life;

        $life->no_of_lifes = Life::MAXIMUM_LIFES;

        return $life;
    }
}
