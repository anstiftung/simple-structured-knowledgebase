<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return response()->json([
        'api_version' => '1.0',
        'state' => 'working',
    ]);
});

// Add protected routes here
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/protected-endpoint', [DashboardControllerController::class, 'index']);
});
