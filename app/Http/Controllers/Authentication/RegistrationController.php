<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\Request;
use App\Dtos\RegistrationDto;
use App\Services\AuthService;
use App\Traits\ResponseNormalizer;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Authentication\RegistrationRequest;
use App\Http\Resources\UserResource;

class RegistrationController extends Controller
{
    use ResponseNormalizer;

    public function __construct(public readonly AuthService $authService) {}

    public function __invoke(RegistrationRequest $request): Response
    {
        $validated  = RegistrationDto::fromArray($request->validated());

        $registeredUser = $this->authService->createUser($validated);

        if ($registeredUser) {

            return $this->created(
                message: "User created Successfully, Proceed to Verify Email",
                data: [
                    'email' => $registeredUser->email,
                ],
            );
        }
        return $this->badResponse("Error Occurred");
    }
}