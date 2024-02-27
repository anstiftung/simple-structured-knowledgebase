<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $title = fake()->name();
        $content = '<h3>' . fake()->words(4, true) . '</h3><p>' . fake()->sentence(3) . '</p>';
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->sentence(20),
            'content' => $content,
            'claps' => fake()->numberBetween(0, 10),
            'created_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-5 months', 'now'),
        ];
    }
}
