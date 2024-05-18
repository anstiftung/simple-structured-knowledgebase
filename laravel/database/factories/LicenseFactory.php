<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class LicenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $externalURL = null;
        if (fake()->boolean(80)) {
            $externalURL = fake()->url();
        }

        $icons = [
            '<img src="/license-icons/cc-by.svg" />',
            '<img src="/license-icons/cc-nc.svg" />',
            '<img src="/license-icons/cc-nd.svg" />',
            '<img src="/license-icons/cc-sa.svg" />',
            '<img src="/license-icons/cc-zero.svg" />'
        ];

        return [
            'title' => fake()->name(),
            'description' => fake()->sentence(2),
            'order' => fake()->unique()->numberBetween(0, 15),
            'icons' => fake()->boolean(80) ? '<img src="/license-icons/cc-logo.svg" />' . fake()->randomElement($icons) : '',
            'external_url' => $externalURL,
            'active' => true
        ];
    }
}
