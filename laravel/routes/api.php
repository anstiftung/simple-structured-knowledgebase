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
    Route::get('/attachedUrls', 'index');
});

Route::controller(AttachedFileController::class)->group(function () {
    Route::get('/attachedFiles', 'index');
    Route::get('/attachedFile/{attachedFile:id}', 'show');
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
    Route::post('/attachedUrl/store', [AttachedUrlController::class, 'store']);
    Route::post('/attachedUrl/update', [AttachedUrlController::class, 'update']);
    Route::post('/attachedFile/store', [AttachedFileController::class, 'store']);
    Route::post('/attachedFile/update', [AttachedFileController::class, 'update']);

    // create and update articles
    Route::post('/article/store', [ArticleController::class, 'store']);
});
