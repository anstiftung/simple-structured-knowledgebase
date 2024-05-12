<?php

namespace Database\Factories;

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
        $isPublished = fake()->boolean(40);

        return [
            'title' => $title,
            'description' => fake()->sentence(50),
            'published' => $isPublished,
            'featured' => $isPublished ? fake()->boolean(40) : false,
            'order' => null,
            'created_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-5 months', 'now'),
        ];
    }
}
