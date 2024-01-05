<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttachedUrl>
 */
class AttachedFileFactory extends Factory
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
            'filename' => Str::slug($title) . '.png',
            'mime_type' => fake()->mimeType(),
            'filesize' => fake()->numberBetween(10000, 1000000),
            'preview_file' => Str::slug($title) . '_preview.png',
            'source' => fake()->sentence(3),
        ];
    }
}
