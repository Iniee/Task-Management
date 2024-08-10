<?php

namespace App\Services;

use App\Dtos\RegistrationDto;
use App\Models\User;
use App\Services\OtpService;

class AuthService
{
    public function __construct(public readonly OtpService $otpService) {}

    public function generateAuthToken(User $user): string
    {
        $user->tokens()->delete();
        $token = $user->createToken("$user->name token")->plainTextToken;
        return $token;
    }

    public function verifyEmailAddress(array $data): array
    {
        $user = User::where("email", $data['email'])->first();

        if (!$user) {
            return ["message" => "User doesn't exist", "status" => false, "data" => null];
        }

        if ($this->otpService->verifyOtp($data['code'], $user->id)) {
            $user->email_verified_at = now();
            $user->save();
            return ["message" => "OTP Verified Successfully", "status" => true, "data" => $user];
        } else {
            return ["message" => "Invalid Data", "status" => false, "data" => null];
        }
        return ["message" => "Email already verified proceed to login", "status" => false, "data" => null];
    }

    public function createUser(RegistrationDto $data): User
    {
        $userData = $data->toArray();

        $user = User::create($userData);

        $this->otpService->sendOtp($user);

        return $user;
    }
}