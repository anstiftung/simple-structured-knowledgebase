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
        $isFilled = fake()->boolean(70);
        $isDeleted = fake()->boolean(20);

        return [
            'title' => $isFilled ? $title : null,
            'description' => $isFilled ? fake()->sentence(3) : null,
            'url' => fake()->url(),
            'preview_file' => Str::slug($title) . '.png',
            'crawled_at' => fake()->dateTime(),
            'crawled_status' => fake()->numberBetween(200, 500),
            'created_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'deleted_at' => $isDeleted ? fake()->dateTimeBetween('-3 months', 'now') : null
        ];
    }
}
