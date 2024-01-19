<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
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

        $articles = Article::where('title', 'like', '%' . $searchQuery . '%')->orderBy('created_at', 'DESC')->get();
        $attachedUrls = AttachedUrl::where('title', 'like', '%' . $searchQuery . '%')->orderBy('created_at', 'DESC')->get();
        $attachedFiles = AttachedFile::where('title', 'like', '%' . $searchQuery . '%')->orderBy('created_at', 'DESC')->get();

        $numArticles = $articles->count();
        $numAttachedUrls = $attachedUrls->count();
        $numAttachediles = $attachedFiles->count();
        $numResults = $numArticles + $numAttachedUrls + $numAttachediles;

        $result = [
            'data' => [
                'articles' => ArticleResource::collection($articles),
                'attached_urls' => AttachedUrlResource::collection($attachedUrls),
                'attached_files' => AttachedFileResource::collection($attachedFiles)
            ],
            'meta' => [
                'num_articles' => $numArticles,
                'num_attached_urls' => $numAttachedUrls,
                'num_attached_files' => $numAttachediles,
                'num_results' => $numResults
            ]
        ];
        return response()->json($result);
    }
}
