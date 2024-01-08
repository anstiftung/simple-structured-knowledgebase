<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CollectionController;
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
    Route::get('/article/{article}', 'show');
});

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'search');
});

Route::controller(AttachedUrlController::class)->group(function () {
    Route::post('/attachedUrl/store', 'store');
    Route::post('/attachedUrl/update', 'update');
});

Route::controller(AttachedFileController::class)->group(function () {
    Route::post('/attachedFile/store', 'store');
    Route::post('/attachedFile/update', 'update');
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
    Route::get('/article/add', [ArticleController::class, 'store']);
    Route::get('/attachment/url/add', [AttachedUrlController::class, 'store']);
    Route::get('/attachment/file/add', [AttachedFileController::class, 'store']);
    Route::get('/collection/add', [CollectionController::class, 'store']);
});
