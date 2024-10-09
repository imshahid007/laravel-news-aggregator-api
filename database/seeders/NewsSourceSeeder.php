<?php

namespace Database\Seeders;

use App\Models\NewsSource;
use Illuminate\Database\Seeder;

class NewsSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        try {
            $newsSources = $this->predefined();
            // Create the news sources
            if (! empty($newsSources) && is_array($newsSources)) {
                //
                NewsSource::insert($newsSources);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * The news sources to be inserted (predefined) into the database.
     */
    private function predefined()
    {
        return [
            ['name' => 'NewsAPI', 'url' => 'https://newsapi.org', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'TheGuardian', 'url' => 'https://theguardian.com', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'New York Times', 'url' => 'https://www.nytimes.com', 'created_at' => now(), 'updated_at' => now()],
        ];
    }
}
