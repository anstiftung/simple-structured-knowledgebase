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

        $isFilled = fake()->boolean(70);
        $isDeleted = fake()->boolean(20);

        return [
            'title' => $isFilled ? $title : null,
            'description' => $isFilled ? fake()->sentence(3) : null,
            'filename' => Str::slug($title) . '.png',
            'approved' => fake()->boolean(70),
            'mime_type' => fake()->mimeType(),
            'filesize' => fake()->numberBetween(10000, 1000000),
            'preview_file' => Str::slug($title) . '_preview.png',
            'source' => $isFilled ? fake()->sentence(3) : null,
            'created_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'deleted_at' => $isDeleted ? fake()->dateTimeBetween('-3 months', 'now') : null
        ];
    }
}
