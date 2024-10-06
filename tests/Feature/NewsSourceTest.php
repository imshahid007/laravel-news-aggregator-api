<?php

//

use Database\Seeders\NewsSourceSeeder;

beforeEach(function () {
    $this->seed(NewsSourceSeeder::class); // Assuming we have a Seeder class that populates the database with test data
});

//
it('can fetch all news sources', function () {
    // Hit the endpoint
    $response = $this->getJson('/api/news-sources');
    // Assert
    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonFragment(['name' => 'BBC News', 'url' => 'https://www.bbc.co.uk/news'])
        ->assertJsonFragment(['name' => 'NewsAPI', 'url' => 'https://newsapi.org']);
});
