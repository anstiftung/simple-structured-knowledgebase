<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Collection;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Http\Resources\RecipeResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\IngredientResource;

class SearchController extends Controller
{
    /**
     * Run Search
     */
    public function search(Request $request)
    {
        $searchQuery = $request->query('query', false);

        $collections = Collection::where('title', 'like', '%' . $searchQuery . '%')->get();
        $recipes = Recipe::where('title', 'like', '%' . $searchQuery . '%')->get();
        $ingredients = Ingredient::where('title', 'like', '%' . $searchQuery . '%')->get();

        $numCollections = $collections->count();
        $numRecipes = $recipes->count();
        $numIngredients = $ingredients->count();
        $numResults = $numCollections + $numRecipes + $numIngredients;

        $result = [
            'data' => [
                'collections' => CollectionResource::collection($collections),
                'recipes' => RecipeResource::collection($recipes),
                'ingredients' => IngredientResource::collection($ingredients)
            ],
            'meta' => [
                'numCollections' => $numCollections,
                'numRecipes' => $numRecipes,
                'numIngredients' => $numIngredients,
                'numResults' => $numResults
            ]
        ];
        return response()->json($result);
    }
}
