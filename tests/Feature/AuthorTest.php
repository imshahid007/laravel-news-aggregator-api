<?php

it('returns ', function () {
    // Create a author
    \App\Models\Author::factory()->count(7)->create();
    // Hit the endpoint
    $response = $this->getJson('/api/authors');
    // Assert that the response is successful
    $response->assertStatus(200);
    // Assert it returns 7 authors inside the data
    $response->assertJsonCount(7, 'data');
});
