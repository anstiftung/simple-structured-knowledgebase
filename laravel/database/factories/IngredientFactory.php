<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
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
            'description' => fake()->sentence(3),
            'source' => fake()->sentence(3)
        ];
    }
}
