<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the author with name "Unknown Author" => Due to the fact that some news articles api may not have an author
        \App\Models\Author::factory()->create([
            'name' => 'Unknown Author',
            'slug' => 'unknown-author',
        ]);
    }
}
