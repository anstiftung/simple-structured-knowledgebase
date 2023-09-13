<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Ingredient;
use App\Models\License;
use App\Models\Recipe;
use App\Models\User;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    protected $faker;

    private $numLicenses = 4;
    private $numUsers = 10;
    private $numIngredients = 90;
    private $numRecipes = 30;
    private $numCollections = 10;

    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        License::truncate();
        User::truncate();
        Ingredient::truncate();
        Recipe::truncate();
        Collection::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        License::factory($this->numLicenses)->create();

        User::factory($this->numUsers)->create();

        Ingredient::factory()->count($this->numIngredients)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'license_id' => License::all()->random()->id,
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))
            ->create();

        $recipes = Recipe::factory()->count($this->numRecipes)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))
            ->create();

        foreach ($recipes as $recipe) {
            $numIngredientsToAttach = rand(1, $this->numIngredients);
            $ingredients = Ingredient::get()->random($numIngredientsToAttach);
            $recipe->ingredients()->attach($ingredients);
        }

        $collections = Collection::factory($this->numCollections)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'created_by_id' => User::all()->random()->id,
                    'updated_by_id' => User::all()->random()->id
                ],
            ))->create();

        foreach ($collections as $collection) {
            $numRecipesToAttach = rand(1, $this->numRecipes);
            $recipes = Recipe::get()->random($numRecipesToAttach);
            $collection->recipes()->attach($recipes);
        }
    }
}
