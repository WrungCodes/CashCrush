<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'username' => $this->user->username,
            'naira_balance' => $this->naira_balance,
            'coin_balance' => $this->coin_balance,
            'points' => $this->point
        ];
    }
}
