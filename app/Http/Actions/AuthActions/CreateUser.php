<?php

namespace App\Http\Actions\AuthActions;

use App\Helpers\Generate;
use App\Helpers\SendEmail;
use App\Http\Requests\Auth\Register;
use App\User;
use App\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUser
{
    private $request;

    public function __construct(Register $request)
    {
        $this->request = $request;
    }

    public function execute(): User
    {
        return $this->createNewUser();
    }

    public function createNewUser(): User
    {
        try {
            $user = User::create([
                'username' => $this->request->username,
                'email' => $this->request->email,
                'password' => Hash::make($this->request->password),
                'user_type_id' => UserType::PLAYER_USER,
                'email_token' => Generate::generateToken()
            ]);

            $emailData = Generate::GenerateVerification($user);

            SendEmail::verificationMail($user, $emailData);

            return $user;
        } catch (\Throwable $th) {
            abort(500, "Unable to create user", []);
        }
    }
}
