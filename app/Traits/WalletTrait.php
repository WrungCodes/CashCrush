<?php

namespace App\Traits;

use App\User;

trait WalletTrait
{
    public function DebitCoin(User $user, int $price): int
    {
        $initialBalance = $user->profile->coin_balance;

        if (!$this->balanceIsEnough($initialBalance, $price)) {
            abort(422, "insufficient fund");
        }

        $currentBalance = $initialBalance - $price;

        $user->profile()->update([
            'coin_balance' => $currentBalance
        ]);

        return $currentBalance;
    }

    public function CreditCoin(User $user, int $price)
    {
    }

    public function DebitNaira(User $user, int $price)
    {
    }

    public function CreditNaira(User $user, int $price)
    {
    }

    private function balanceIsEnough($balance, $amount): bool
    {

        if ($balance < $amount) {
            return false;
        }
        return true;
    }
}
