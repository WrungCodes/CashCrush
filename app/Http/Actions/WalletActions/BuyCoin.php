<?php

namespace App\Http\Actions\WalletActions;

use App\Helpers\Find;
use App\Logics\Checkout\Checkout;
use App\Logics\Checkout\Gateways\Paystack\PaystackCheckout;
use App\Services\GladePay\PaystackCheckout as PaystackCheckoutService;
use App\Traits\InventoryTrait;
use App\Traits\WalletTrait;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\DB;

class BuyCoin
{

    protected $user;

    protected $coinDealUid;


    public function __construct(User $user, string $coinDealUid)
    {
        $this->user = $user;
        $this->coinDealUid = $coinDealUid;
    }

    public function execute()
    {
        return $this->processBuy();
    }

    public function processBuy()
    {
        $coinDeal = Find::GetCoinObjectWithUid($this->coinDealUid);

        $response = (new Checkout(new PaystackCheckout(new PaystackCheckoutService())))
            ->process($this->user->email,  $coinDeal->price);

        $this->user->transactions()->create([
            'amount' => $coinDeal->price,
            'user_id' => $this->user->id,
            'platform' => $response['platform'],
            'transaction_type' => Transaction::TRANSACTION_TYPE_COIN,
            'transaction_type_uid' => $coinDeal->uid,
            'transaction_ref' => $response['reference'],
            'access_code' =>  $response['access_code'],
            'status' => Transaction::TRANSACTION_PENDING
        ]);

        return $response;
    }
}
