<?php

namespace App\Http\Actions;

use App\Life;
use App\User;

class AddLife
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function execute()
    {
        return $this->reduce();
    }

    public function reduce()
    {
        $lifes = $this->user->life;

        $currentLifes = $lifes->no_of_lifes;

        if ($currentLifes == Life::MAXIMUM_LIFES) {
            abort(422, "LIfe Full");
        }

        $lifes->update([
            'no_of_lifes' => $newLifes = $currentLifes + 1
        ]);

        return $newLifes;
    }
}
