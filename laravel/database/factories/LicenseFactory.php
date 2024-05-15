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
        if (fake()->boolean(20)) {
            $externalURL = fake()->url();
        }

        $icons = ['cc-by.svg', 'cc-logo.svg', 'cc-nc.svg', 'cc-nd.svg', 'cc-sa.svg', 'cc-zero.svg'];

        return [
            'title' => fake()->name(),
            'description' => fake()->sentence(2),
            'order' => fake()->unique()->numberBetween(0, 15),
            'external_url' => $externalURL,
            'active' => true
        ];
    }
}
