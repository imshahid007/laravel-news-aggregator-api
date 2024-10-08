<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first(),
            'news_source_id' => NewsSource::inRandomOrder()->first(),
            'author_id' => Author::inRandomOrder()->first(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->text(500),
            'url' => $this->faker->url(),
            'image' => $this->faker->imageUrl(),
            'published_at' => $this->faker->dateTime(),
        ];
    }
}
