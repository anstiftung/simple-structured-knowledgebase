<?php

use App\Http\Controllers\AttachedUrlController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;

Route::controller(RecipeController::class)->group(function () {
    Route::get('/recipes', 'index');
    Route::get('/recipe/{slug}', 'show');
});

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'search');
});

Route::controller(AttachedUrlController::class)->group(function () {
    Route::post('/attachedUrl/store', 'store');
    Route::post('/attachedUrl/update', 'update');

});

Route::get('/', function () {
    return response()->json([
        'api_version' => '1.0',
        'state' => 'working',
    ]);
});
