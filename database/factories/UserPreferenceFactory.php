<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;
use App\Models\NewsSource;
use App\Models\Author;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'preferred_categories' => Category::pluck('id')->random(1)->toArray(),
            'preferred_sources' => NewsSource::pluck('id')->random(1)->toArray(),
            'preferred_authors' => Author::pluck('id')->random(1)->toArray(),
        ];
    }
}
