<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CollectionResource;
use App\Rules\CollectionStateValidator;

class CollectionController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $collections = Collection::when($request->featured == true, function ($query) {
            return $query->featured()->orderBy('order', 'ASC');
        })
        ->when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })
        ->when(empty($this->user), function ($query) {
            $query->published();
        })
        ->orderBy('updated_at', 'DESC')->paginate();

        // eager load the articles: scope to published ones, when not authenticated
        $collections->load(['articles' => function ($query) {
            if (!$this->user) {
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
        if (!$this->user->can('add collections')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
             'title' => 'required|max:255',
             'description' => 'required|max:1000',
             'published' => 'boolean',
             'articles.*.id' => 'exists:articles,id',
             'articles.*.order' => 'integer'
         ]);

        $collection = Collection::create([
           'title' => $request->title,
           'slug' => Str::slug($request->title),
           'description' => $request->description,
           'published' => $request->published,
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
        if (!$this->user && !$collection->published) {
            // @todo: this is not the default not found response as generated by the Route Model binding functionality
            abort(404);
        }

        // eager load the articles: scope to published ones, when not authenticated
        $collection->load(['articles' => function ($query) {
            if (!$this->user) {
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
        if (!$this->user->can('edit collections')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
             'title' => 'required|max:255',
             'description' => 'required|max:1000',
             'published' => ['boolean', new CollectionStateValidator($collection)],
             'articles.*.id' => 'exists:articles,id',
             'articles.*.order' => 'integer',
             'created_by.id' => 'exists:users,id'
         ]);

        $collection->update([
            'title' => $request->title,
            'description' => $request->description,
            'published' => $request->published
        ]);

        if ($this->user->can('feature collections')) {
            $collection->update([
                'featured' => $request->featured
            ]);
        }

        // conditional update article creator
        if ($this->user->can('edit collection creator')) {
            $collection->update([
                'created_by_id' => $request->created_by['id']
            ]);
        }

        if ($request->has('articles')) {
            $articles = [];
            foreach($request->articles ?? [] as $article) {
                $articles[$article['id']] = ['order' => $article['order']];
            }
            $collection->articles()->sync($articles);
        }

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
