<?php

namespace App\Services;

use App\Models\User;
use App\Models\OtpCode;
use App\Notifications\VerificationEmailNotification;

class OtpService
{

    public function sendOtp(User $user)
    {
        $verificationCode = $this->generateOtp();
        OtpCode::query()
            ->where('user_id', $user->id)
            ->notExpired()
            ->delete();

        $otp = new OtpCode([
            'code' => $verificationCode,
            'expires_at' => now()->addMinutes(10)
        ]);

        $user->otpCode()->save($otp);

        $user->notify(new VerificationEmailNotification($user->name, $otp->code));
    }

    public function verifyOtp($code, $userId)
    {
        $otp = OtpCode::where('code', $code)
            ->where("user_id", $userId)
            ->first();
        if (!$otp) {
            return false;
        }
        if ($otp->expires_at->isPast()) {
            $otp->delete();
            return false;
        }

        $otp->delete();
        return true;
    }


    private function generateOtp(): int
    {
        return mt_rand(1000, 9999);
    }
}