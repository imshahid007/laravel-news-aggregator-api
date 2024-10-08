<?php

//

beforeEach(function () {
    // Seed the database
    $this->seed();
    // Run the Article factory
    \App\Models\Article::factory()->count(10)->create();

    // Create a user and authenticate it via Sanctum
    $this->user = \App\Models\User::factory()->create();
    $this->token = $this->user->createToken('test')->plainTextToken;
});

// It will return a collection of articles with pagination for authenticated users
it('returns a collection of articles with pagination', function () {
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/articles', [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns 10 articles inside the data
    $response->assertJsonCount(10, 'data');
    // Assert it returns the pagination links
    $response->assertJsonStructure([
        'links' => ['first', 'last', 'prev', 'next'],
    ]);
});

// It does not return a collection of articles for unauthenticated users
it('does not return a collection of articles for unauthenticated users', function () {
    // Hit the endpoint without the user's token
    $response = $this->getJson('/api/articles');
    // Assert that the response is unauthorized
    $response->assertStatus(401);
    // Assert it returns the error message
    $response->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});

// It fetch a single article for authenticated users
it('fetches a single article', function () {
    // Get an article
    $article = \App\Models\Article::first();
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/articles/'.$article->id, [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns the article
    $response->assertJson([
        'data' => [
            'id' => $article->id,
            'title' => $article->title,
        ],
    ]);
});

// It searches for articles to allow filtering articles by keyword
it('searches for articles', function () {
    // Get an article
    $article = \App\Models\Article::first();
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/articles/search?q='.$article->title, [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns the collection of articles
    $response->assertJsonFragment([
        'title' => $article->title,
    ]);
});

// It searches for articles to allow filtering articles by date
it('searches for articles by date', function () {
    // Get an article
    $article = \App\Models\Article::first();
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/articles/search?date='.$article->published_at->format('Y-m-d'), [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    //
    $response->assertJsonFragment([
        'title' => $article->title,
    ]);
});

// It searches for articles to allow filtering articles by category
it('searches for articles by category', function () {
    // Get an article
    $article = \App\Models\Article::first();
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/articles/search?category='.$article->category_id, [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns the collection of articles
    $response->assertJsonFragment([
        'title' => $article->title,
    ]);
});

// It searches for articles to allow filtering articles by news source
it('searches for articles by news source', function () {
    // Get an article
    $article = \App\Models\Article::first();
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/articles/search?source='.$article->news_source_id, [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns the collection of articles
    $response->assertJsonFragment([
        'title' => $article->title,
    ]);
});

// It searches for articles to allow filtering articles by keyword, date, category and source
it('searches for articles by keyword, date, category and source', function () {
    // Get an article
    $article = \App\Models\Article::first();
    // Hit the endpoint with the user's token
    $response = $this->getJson('/api/articles/search?q='.$article->title.'&date='.$article->published_at->format('Y-m-d').'&category='.$article->category_id.'&source
='.$article->news_source_id, [
        'Authorization' => 'Bearer '.$this->token,
    ]);
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns the collection of articles
    $response->assertJsonFragment([
        'title' => $article->title,
    ]);
});

// It does not search for articles for unauthenticated users
it('does not search for articles for unauthenticated users', function () {
    // Get an article
    $article = \App\Models\Article::first();
    // Hit the endpoint without the user's token
    $response = $this->getJson('/api/articles/search?q='.$article->title);
    // Assert that the response is unauthorized
    $response->assertStatus(401);
    // Assert it returns the error message
    $response->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});
