<?php

namespace App\Traits;

use App\CoinHistory;
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

        $this->CreateCoinHistory($initialBalance, $currentBalance, $price, $user, CoinHistory::DEBIT_TYPE);

        return $currentBalance;
    }

    public function CreditCoin(User $user, int $price)
    {
        $initialBalance = $user->profile->coin_balance;

        $currentBalance = $initialBalance + $price;

        $user->profile()->update([
            'coin_balance' => $currentBalance
        ]);

        $this->CreateCoinHistory($initialBalance, $currentBalance, $price, $user, CoinHistory::CREDIT_TYPE);

        return $currentBalance;
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

    private function CreateCoinHistory(int $intitialAmount, int $finalAmount, int $price, User $user, string $type)
    {
        $user->coinHistory()->create([
            'initial_amount' => $intitialAmount,
            'final_amount' => $finalAmount,
            'type' => $type,
            'amount' => $price
        ]);
    }

    private function CreateNairaHistory(float $intitialAmount, float $finalAmount, float $price, User $user, string $type)
    {
        $user->nairaHistory()->create([
            'initial_amount' => $intitialAmount,
            'final_amount' => $finalAmount,
            'type' => $type,
            'amount' => $price
        ]);
    }
}
