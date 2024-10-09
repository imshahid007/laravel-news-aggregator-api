<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NewsSourceController;
use App\Http\Controllers\Api\UserPreferenceController;
use Illuminate\Support\Facades\Route;

//
Route::prefix('auth')->group(function () {
    // Group routes by middleware
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    // Password reset routes
    Route::post('password/email', [AuthController::class, 'sendPasswordResetLinkEmail']);
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
});

// Authenticated user routes
Route::middleware('auth:sanctum')->group(function () {
    // Get authenticated user
    Route::get('user', [AuthController::class, 'getAuthenticatedUser']);
    // Logout
    Route::post('logout', [AuthController::class, 'logout']);

    // Rate Limiting
    Route::middleware('throttle:60,1')->group(function () {
        // Articles routes
        Route::prefix('articles')->group(function () {
            // Articles
            Route::get('/', [ArticleController::class, 'index']);
            // Search
            Route::get('search', [ArticleController::class, 'search']);
            // Get a single article
            Route::get('{article}', [ArticleController::class, 'show']);
        });

        // User preferences routes
        Route::prefix('user/preferences')->group(function () {
            // Get user preferences
            Route::get('/', [UserPreferenceController::class, 'show']);
            // Update user preferences
            Route::post('/', [UserPreferenceController::class, 'store']);

            // User personalized feed
            Route::get('/feed', [UserPreferenceController::class, 'personalizedFeed']);

        });
    });

});

/**
 * Public routes
 */

// Get Categories
Route::get('categories', CategoryController::class);
// Get News Sources
Route::get('news-sources', NewsSourceController::class);
// Get Authors
Route::get('authors', AuthorController::class);
