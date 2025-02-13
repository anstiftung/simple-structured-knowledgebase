<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $deletedAt = null;
        if (fake()->boolean(20)) {
            $deletedAt = fake()->dateTimeBetween('-5 months', 'now');
        }
        return [
            'content' => fake()->sentence(50),
            'deleted_at' => $deletedAt,
            'created_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-5 months', 'now'),
        ];
    }
}
