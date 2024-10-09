<?php

//

use Database\Seeders\CategorySeeder;

beforeEach(function () {
    $this->seed(CategorySeeder::class); // Assuming we have a Seeder class that populates the database with test data
});

// It will return a collection of categories
it('returns a collection of categories', function () {
    // Hit the endpoint
    $response = $this->getJson('/api/categories');
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns 7 categories inside the data
    $response->assertJsonCount(3, 'data');
});
