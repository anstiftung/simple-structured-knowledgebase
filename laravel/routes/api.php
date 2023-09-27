<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\IngredientController;

Route::controller(IngredientController::class)->group(function () {
    Route::get('/ingredients', 'index');
    Route::get('/ingredient/{slug}', 'show');
});

Route::controller(RecipeController::class)->group(function () {
    Route::get('/recipes', 'index');
    Route::get('/recipe/{slug}', 'show');
});

Route::controller(CollectionController::class)->group(function () {
    Route::get('/collections', 'index');
    Route::get('/collection/{slug}', 'show');
});

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'search');
});

Route::get('/', function () {
    return response()->json([
        'api_version' => '1.0',
        'state' => 'working',
    ]);
});
