<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Collection;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\AttachedUrlResource;
use App\Http\Resources\AttachedFileResource;

class SearchController extends Controller
{
    /**
     * Run Search
     */
    public function search(Request $request)
    {
        $searchQuery = $request->query('query', false);
        $images_only = $request->query('images', false);


        $collections = Collection::where('title', 'like', '%' . $searchQuery . '%')->orderBy('created_at', 'DESC')->get();
        $articles = Article::where('title', 'like', '%' . $searchQuery . '%')->orderBy('created_at', 'DESC')->get();
        $attachedUrls = AttachedUrl::where('title', 'like', '%' . $searchQuery . '%')->orderBy('created_at', 'DESC')->get();
        $attachedFiles = AttachedFile::where('title', 'like', '%' . $searchQuery . '%')
            ->when($images_only, function ($query) {
                $query->whereIn('mime_type', ['image/png','image/jpg','image/jpeg']);
            })
            ->orderBy('created_at', 'DESC')->get();

        $numCollections = $collections->count();
        $numArticles = $articles->count();
        $numAttachedUrls = $attachedUrls->count();
        $numAttachediles = $attachedFiles->count();
        $numResults = $numCollections + $numArticles + $numAttachedUrls + $numAttachediles;

        $result = [
            'data' => [
                'collections' => CollectionResource::collection($collections),
                'articles' => ArticleResource::collection($articles),
                'attached_urls' => AttachedUrlResource::collection($attachedUrls),
                'attached_files' => AttachedFileResource::collection($attachedFiles)
            ],
            'meta' => [
                'num_collections' => $numCollections,
                'num_articles' => $numArticles,
                'num_attached_urls' => $numAttachedUrls,
                'num_attached_files' => $numAttachediles,
                'num_results' => $numResults
            ]
        ];
        return response()->json($result);
    }
}
