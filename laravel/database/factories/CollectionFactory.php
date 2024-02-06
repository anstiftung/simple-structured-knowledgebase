<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collection>
 */
class CollectionFactory extends Factory
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
            'slug' => Str::slug($title),
            'description' => fake()->sentence(50),
            'featured' => fake()->boolean(40),
            'order' => null,
            'created_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-5 months', 'now'),
        ];
    }
}