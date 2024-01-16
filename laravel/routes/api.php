<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AttachedUrlController;
use App\Http\Controllers\AttachedFileController;

use App\Http\Controllers\LicenseController;

Route::controller(LicenseController::class)->group(function () {
    Route::get('/licenses', 'index');
});

Route::controller(ArticleController::class)->group(function () {
    Route::get('/articles', 'index');
    Route::get('/article/{article:slug}', 'show');
});

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'search');
});

Route::controller(AttachedUrlController::class)->group(function () {
    Route::get('/attached-urls', 'index');
});

Route::controller(AttachedFileController::class)->group(function () {
    Route::get('/attached-files', 'index');
    Route::get('/attached-file/{attachedFile:id}', 'show');
});

Route::get('/', function () {
    return response()->json([
        'api_version' => '1.0',
        'state' => 'working',
    ]);
});

// Add protected routes here
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // create and update attachments
    Route::post('/attached-url/store', [AttachedUrlController::class, 'store']);
    Route::post('/attached-url/update', [AttachedUrlController::class, 'update']);
    Route::post('/attached-file/store', [AttachedFileController::class, 'store']);
    Route::post('/attached-file/update', [AttachedFileController::class, 'update']);

    // create and update articles
    Route::post('/article', [ArticleController::class, 'store']);
    Route::patch('/article/{article:slug}', [ArticleController::class, 'update']);
});
