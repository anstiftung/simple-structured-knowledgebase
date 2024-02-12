<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CollectionResource;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $collections = Collection::when($request->featured == true, function ($query) {
            return $query->featured()->orderBy('order', 'ASC');
        })->when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })->orderBy('updated_at', 'DESC')->paginate();

        // eager load the articles: scope to published ones, when not authenticated
        $collections->load(['articles' => function ($query) use ($user) {
            if (!$user) {
                $query->published();
            }
        }]);

        return CollectionResource::collection($collections);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('add collections')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
             'title' => 'required|max:255',
             'description' => 'required|max:1000',
             'articles.*.id' => 'exists:articles,id',
             'articles.*.order' => 'integer'
         ]);

        $collection = Collection::create([
           'title' => $request->title,
           'slug' => Str::slug($request->title),
           'description' => $request->description,
           'content' => $request->content
        ]);

        $articles = [];
        foreach($request->articles ?? [] as $article) {
            $articles[$article['id']] = ['order' => $article['order']];
        }
        $collection->articles()->sync($articles);

        $collection->load(['articles']);

        return new CollectionResource($collection);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        $user = Auth::user();
        // eager load the articles: scope to published ones, when not authenticated
        $collection->load(['articles' => function ($query) use ($user) {
            if (!$user) {
                $query->published();
            }
        }]);

        return new CollectionResource($collection);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Collection $collection, Request $request)
    {
        $user = Auth::user();
        if (!$user->can('edit collections')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
             'title' => 'required|max:255',
             'description' => 'required|max:1000',
             'articles.*.id' => 'exists:articles,id',
             'articles.*.order' => 'integer'
         ]);

        $collection->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($user->can('feature collections')) {
            $collection->update([
                'featured' => $request->featured
            ]);
        }

        $articles = [];
        foreach($request->articles ?? [] as $article) {
            $articles[$article['id']] = ['order' => $article['order']];
        }
        $collection->articles()->sync($articles);

        $collection->load(['articles']);

        return new CollectionResource($collection);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
