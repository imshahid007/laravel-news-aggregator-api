<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the categories
        $this->call(CategorySeeder::class);
        // Seed the news sources
        $this->call(NewsSourceSeeder::class);
        // Seed the author
        $this->call(AuthorSeeder::class);
    }
}
