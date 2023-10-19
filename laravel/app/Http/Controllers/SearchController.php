<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use App\Http\Resources\RecipeResource;
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

        $recipes = Recipe::where('title', 'like', '%' . $searchQuery . '%')->get();
        $attachedUrls = AttachedUrl::where('title', 'like', '%' . $searchQuery . '%')->get();
        $attachedFiles = AttachedFile::where('title', 'like', '%' . $searchQuery . '%')->get();

        $numRecipes = $recipes->count();
        $numAttachedUrls = $attachedUrls->count();
        $numAttachediles = $attachedFiles->count();
        $numResults = $numRecipes + $numAttachedUrls + $numAttachediles;

        $result = [
            'data' => [
                'recipes' => RecipeResource::collection($recipes),
                'attached_urls' => AttachedUrlResource::collection($attachedUrls),
                'attached_files' => AttachedFileResource::collection($attachedFiles)
            ],
            'meta' => [
                'num_recipes' => $numRecipes,
                'num_attached_urls' => $numAttachedUrls,
                'num_attached_files' => $numAttachediles,
                'num_results' => $numResults
            ]
        ];
        return response()->json($result);
    }
}
