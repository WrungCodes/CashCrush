<?php

namespace App\Http\Actions;

use App\User;

class ReduceLife
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

        if ($currentLifes < 1) {
            abort(422, "You have finished your Lifes");
        }

        $lifes->update([
            'no_of_lifes' => $newLifes = $currentLifes - 1
        ]);

        return $newLifes;
    }
}
