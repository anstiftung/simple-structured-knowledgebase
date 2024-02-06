<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     * Avaible params:
     * – page
     * – creatorId -> filter by creator
     */
    public function index(Request $request)
    {
        $articles = Article::when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })->orderBy('updated_at', 'DESC')->paginate();

        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('add articles')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
             'title' => 'required|max:255',
             'description' => 'required|max:1000',
             'content' => 'present|string|nullable'
         ]);

        $newArticle = Article::create([
           'title' => $request->title,
           'slug' => Str::slug($request->title),
           'description' => $request->description,
           'content' => $request->content
        ]);

        return new ArticleResource($newArticle);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article->load(['attached_files', 'attached_urls', 'collections']);

        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Article $article, Request $request)
    {
        $user = Auth::user();
        if (!$user->can('edit articles')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:1000',
            'content' => 'present|string|nullable'
        ]);

        $article->update([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content
        ]);

        $article->load(['attached_files', 'attached_urls']);
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}