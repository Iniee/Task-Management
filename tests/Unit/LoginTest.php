<?php

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

test('users can authenticate using the login', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route("login"), [
        'email' => $user->email,
        'password' => 'password',
        'remember' => true
    ]);

    $response->assertStatus(200);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
        'remember' => true
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('logout'));

    $response->assertStatus(200);
});