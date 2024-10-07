<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;

// Test for user login
it('logs in a user', function () {
    // Create a user
    $user = User::factory()->create();
    // Login the user
    $response = $this->postJson('api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'android',
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'token',
    ]);
});

// Test for user registration
it('registers a user', function () {
    // Register the user
    $response = $this->postJson('api/auth/register', [
        'name' => 'John Doe',
        'email' => 'john@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'device_name' => 'android',
    ]);
    $response->assertStatus(201);
    $response->assertJsonStructure([
        'token',
    ]);
});

// Test for user logout
it('logs out a user', function () {
    // Create a user
    $user = User::factory()->create();
    // Login the user
    $response = $this->postJson('api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'android',
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'token',
    ]);
    // Logout the user
    $response = $this->postJson('api/logout', [], [
        'Authorization' => 'Bearer '.$response['token'],
    ]);
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Logged out successfully',
    ]);
});

// Test for getting the authenticated user
it('gets the authenticated user', function () {
    // Create a user
    $user = User::factory()->create();
    // Login the user
    $response = $this->postJson('api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'android',
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'token',
    ]);
    // Get the authenticated user
    $response = $this->getJson('api/user', [
        'Authorization' => 'Bearer '.$response['token'],
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'email',
            'created_at',
        ],
    ]);
});

// Test for sending password reset link email
it('sends password reset link email', function () {
    // Create a user
    $user = User::factory()->create();
    // Send password reset link email
    $response = $this->postJson('api/auth/password/email', [
        'email' => $user->email,
    ]);
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'We have emailed your password reset link.',
    ]);
});

// Test for resetting password
it('resets password', function () {
    // Create a user
    $user = User::factory()->create();
    // Send password reset link email
    $response = $this->postJson('api/auth/password/email', [
        'email' => $user->email,
    ]);
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'We have emailed your password reset link.',
    ]);
    // Create a token for the user
    $token = Password::broker()->createToken($user);
    // Reset password
    $response = $this->postJson('api/auth/password/reset', [
        'email' => $user->email,
        'token' => $token,
        'password' => 'newpassword',
        'password_confirmation' => 'newpassword',
    ]);
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Your password has been reset.',
    ]);
});
