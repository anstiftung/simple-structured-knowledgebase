<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttachedUrl>
 */
class AttachedUrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->name();

        return [
            'title' => $title,
            'description' => fake()->sentence(3),
            'url' => fake()->url(),
            'preview_file' => Str::slug($title) . '.png',
            'crawled_at' => fake()->dateTime(),
            'crawled_status' => fake()->numberBetween(200, 500)
        ];
    }
}
