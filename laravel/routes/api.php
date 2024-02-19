<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\UserCredentialsController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\AttachedUrlController;
use App\Http\Controllers\AttachedFileController;
use App\Http\Controllers\CollectionListController;

Route::controller(LicenseController::class)->group(function () {
    Route::get('/licenses', 'index');
});

Route::controller(StateController::class)->group(function () {
    Route::get('/states', 'index');
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
    Route::get('/attached-file/serve/{attachedFile:id}', 'serve');
});

Route::controller(CollectionController::class)->group(function () {
    Route::get('/collections', 'index');
    Route::get('/collection/{collection:slug}', 'show');
});

Route::get('/', function () {
    return response()->json([
        'api_version' => '1.0',
        'state' => 'working',
    ]);
});

// Add protected routes here
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user-credentials', [UserCredentialsController::class, 'index']);

    // create and update attachments
    Route::post('/attached-url', [AttachedUrlController::class, 'store']);
    Route::patch('/attached-url', [AttachedUrlController::class, 'update']);

    Route::post('/attached-file', [AttachedFileController::class, 'store']);
    Route::patch('/attached-file', [AttachedFileController::class, 'update']);

    // create and update articles
    Route::post('/article', [ArticleController::class, 'store']);
    Route::patch('/article/{article:slug}', [ArticleController::class, 'update']);

    Route::middleware(['throttle:claps'])->group(function () {
        Route::patch('/article/{article:slug}/clap', [ArticleController::class, 'clap']);
    });

    // create and update collections
    Route::post('/collection', [CollectionController::class, 'store']);
    Route::patch('/collection/{collection:slug}', [CollectionController::class, 'update']);

    // resort collections
    Route::patch('/collections/featured/reorder', [CollectionListController::class, 'reorder']);

    // create/delete comments
    Route::post('/comment', [CommentController::class, 'store']);
    Route::delete('/comment/{comment:id}', [CommentController::class, 'destroy']);

    // list users
    Route::get('/users', [UserController::class, 'index']);
});
