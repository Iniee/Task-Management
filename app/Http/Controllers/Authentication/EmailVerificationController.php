<?php

namespace App\Http\Controllers\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Traits\ResponseNormalizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Authentication\EmailVerificationRequest;
use App\Services\OtpService;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationController extends Controller
{
    use ResponseNormalizer;

    public function __construct(
        public readonly AuthService $authService,
        public readonly OtpService $otpService
    ) {}

    public function verify(EmailVerificationRequest $request): Response
    {
        $validatedData = $request->validated();

        $verified = $this->authService->verifyEmailAddress($validatedData);

        if ($verified['status'] === false) {
            return $this->badResponse(message: $verified['message']);
        }

        if ($verified['data'] instanceof User) {
            $token = $this->authService->generateAuthToken($verified['data']);
            return $this->success(
                [
                    "user" => new UserResource($verified['data']),
                    'token' => $token,
                ]
            );
        }

        return $this->badResponse(message: "Error Occured");
    }

    public function resendOtp(Request $request): Response
    {
        $validated = $request->validate([
            'email' => 'required|email|string',
        ]);
        $user = User::where("email", $validated['email'])->firstorFail();

        if ($user->email_verified_at !== null) {
            return $this->badResponse(message: 'Your email address is already verified, proceed to login');
        }

        $this->otpService->sendOtp($user);
        return $this->success(
            message: 'Verification code has been successfully sent to your email address'
        );
    }

    public function logout(): Response
    {
        auth()->user()->tokens()->delete();
        return $this->customResponse(message: "Logged out successfully");
    }
}