<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

//
Route::prefix('auth')->group(function () {
    // Group routes by middleware
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Password reset routes
    Route::post('password/email', [AuthController::class, 'sendPasswordResetLinkEmail']);
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');

    // Authenticated user routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'getAuthenticatedUser']);
    });
});
