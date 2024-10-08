<?php

use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
use App\Models\UserPreference;

beforeEach(function () {
    // Create a user and authenticate it via Sanctum
    $this->user = \App\Models\User::factory()->create();
    $this->token = $this->user->createToken('test')->plainTextToken;

    // Seed the database
    $this->seed();

    // Run the Article factory
    \App\Models\Article::factory()->count(10)->create();
});

// It will return a collection of user preferences for authenticated users
it('returns a collection of user preferences for authenticated users', function () {
    // Create a user preference
    UserPreference::factory()->create([
        'user_id' => $this->user->id,
    ]);
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/user/preferences', [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);

    // Assert it returns 1 user preference inside the data
    $response->assertJsonCount(5, 'data');
    // Assert it returns the user preference
    $response->assertJsonStructure([
        'data' => [
            'user_id',
            'preferred_categories',
            'preferred_sources',
            'preferred_authors',
            'updated_at',
        ],
    ]);
});

// It does not return a collection of user preferences for unauthenticated users
it('does not return a collection of user preferences for unauthenticated users', function () {
    // Hit the endpoint without the user's token
    $response = $this->getJson('/api/user/preferences');
    // Assert that the response is unauthorized
    $response->assertStatus(401);
    // Assert it returns the error message
    $response->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});

// It shows 404 error when user preferences are not found
it('shows 404 error when user preferences are not found', function () {
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/user/preferences', [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is not found
    $response->assertStatus(404);
    // Assert it returns the error message
    $response->assertJson([
        'message' => 'User Preferences not found',
    ]);
});

// It updates the user preferences for authenticated users
it('updates the user preferences for authenticated users', function () {
    $category_id = Category::first()->id;
    $source_id = NewsSource::first()->id;
    $author_id = Author::first()->id;
    // Create a user preference
    UserPreference::factory()->create([
        'user_id' => $this->user->id,
    ]);
    // Hit the endpoint with the user's token
    $response = $this->postJson('/api/user/preferences', [
        'preferred_categories' => [$category_id],
        'preferred_sources' => [$source_id],
        'preferred_authors' => [$author_id],
    ], [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns the user preference
    $response->assertJsonStructure([
        'data' => [
            'user_id',
            'preferred_categories',
            'preferred_sources',
            'preferred_authors',
            'updated_at',
        ],
    ]);
});

// It fetches the personalized feed for authenticated users
it('fetches the personalized feed for authenticated users', function () {
    // Create a user preference
    UserPreference::factory()->create([
        'user_id' => $this->user->id,
    ]);
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/user/preferences/feed', [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns the articles
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'category',
                'source',
                'author',
                'title',
                'description',
                'content',
                'url',
                'image',
                'published_at',
            ],
        ],
    ]);
});
