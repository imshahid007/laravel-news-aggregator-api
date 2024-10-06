<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        try {
            $categories = $this->predefined();
            // Create the categories
            if (! empty($categories) && is_array($categories)) {
                //
                Category::insert($categories);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    /**
     * The categories to be inserted (predfined) into the database.
     */
    private function predefined()
    {
        // Define the categories
        $categories = [
            ['name' => 'Business', 'slug' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'General', 'slug' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Health', 'slug' => 'health', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Science', 'slug' => 'science', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports', 'slug' => 'sports', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technology', 'slug' => 'technology', 'created_at' => now(), 'updated_at' => now()],
        ];

        return $categories;
    }
}
