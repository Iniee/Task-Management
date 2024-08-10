<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

test('user-registration-success', function () {
    $userData = [
        'email' => 'test@gmail.com',
        'password' => '123456',
        'password_confirmation' => '123456',
        'name' => 'John Doe',
    ];

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'message' => 'User created Successfully, Proceed to Verify Email',
            'data' => [
                'email' => $userData['email'],
            ],
        ]);

    // Fetch the user from the database
    $user = User::where('email', $userData['email'])->first();

    // Ensure the password is hashed correctly
    $this->assertNotNull($user); // Ensure user exists
    $this->assertTrue(Hash::check($userData['password'], $user->password));
});


test('register user failure', function () {
    $response = $this->postJson(route('register'), [
        'email' => 'invalid-email',
        'password' => '123456',
        'password_confirmation' => '12345=46',
        'name' => 'Samuel',
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            'message' => 'The email field must be a valid email address. (and 1 more error)',
            'errors' => [
                'email' => [
                    'The email field must be a valid email address.',
                ],
                'password' => [
                    'The password field confirmation does not match.',
                ],
            ],
        ]);
});