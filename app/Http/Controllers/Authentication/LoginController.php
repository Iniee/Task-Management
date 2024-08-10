<?php

namespace App\Http\Controllers\Authentication;

use App\Models\User;
use App\Services\AuthService;
use App\Traits\ResponseNormalizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Authentication\LoginRequest;

class LoginController extends Controller
{
    use ResponseNormalizer;

    public function __construct(public readonly AuthService $authService) {}
    public function __invoke(LoginRequest $request): Response
    {
        $credentials = $request->only('email', 'password');
        $admin = User::where("email", $request->email)->first();


        if (Auth::once($credentials)) {
            $user = Auth::user();

            $token = $this->authService->generateAuthToken($admin);

            return $this->success(
                [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
            );
        } else {
            return $this->badResponse(
                message: 'Invalid credentials or unauthorized',
            );
        }
    }
}