<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Recipe;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_all_recipes(): void
    {
        $countRecipes = 10;
        Recipe::factory($countRecipes)->create();

        $response = $this->get('/api/recipes');
        $response
            ->assertStatus(200)
            ->assertJsonCount($countRecipes, 'data');
    }

    public function test_index_single_recipe(): void
    {
        $recipe = Recipe::factory(1)->create()->first();

        $response = $this->get('/api/recipe/'.$recipe->slug);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['title' => $recipe->title]);
    }
}
