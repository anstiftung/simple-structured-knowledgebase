<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CollectionResource;
use App\Rules\CollectionStateValidator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

/**
 * @group Collections
 */

class CollectionController extends BaseController
{
    /**
     * Collection Listing
     */
    public function index(Request $request)
    {

        $collections = Collection::when($request->featured == true, function ($query) {
            return $query->featured()->orderBy('order', 'ASC');
        })
        ->when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })
        ->when(empty($this->authUser), function ($query) {
            $query->published();
        })
        ->orderBy('updated_at', 'DESC')->paginate();

        // eager load the articles: scope to published ones, when not authenticated
        $collections->load(['articles' => function ($query) {
            if (!$this->authUser) {
                $query->published();
            }
        }]);

        return CollectionResource::collection($collections);
    }

    /**
     * Collection Save
     *
     * @bodyParam title required string Must not be greater than 255 characters. Example: The Nicest Article ever
     * @bodyParam description required string Must not be greater than 1000 characters. Example: What a lorem ipsum description.
     * @bodyParam published boolean Defaults to false
     * @bodyParam articles object A List of Articles with defined order
     * @bodyParam articles.id int the related article's id
     * @bodyParam articles.order int the related articles sort-order
     *
     * @authenticated
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Collection::class);

        $request->validate([
             'title' => 'required|max:255',
             'description' => 'required|max:1000',
             'published' => 'boolean',
             'articles.*.id' => 'exists:articles,id',
             'articles.*.order' => 'integer'
         ]);

        $collection = Collection::create([
           'title' => $request->title,
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
     * Collection Details
     *
     * Show details of the defined Collection
     */
    public function show(Collection $collection)
    {
        Gate::authorize('view', $collection);

        // eager load the articles: scope to published ones, when not authenticated
        $collection->load(['articles' => function ($query) {
            if (!$this->authUser) {
                $query->published();
            }
        }]);

        return new CollectionResource($collection);
    }

    /**
     * Collection Update
     */
    public function update(Collection $collection, Request $request)
    {
        Gate::authorize('update', $collection);

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

        if ($this->authUser->can('feature collections')) {
            $collection->update([
                'featured' => $request->featured
            ]);
        }

        // conditional update article creator
        if ($this->authUser->can('edit collection creator')) {
            $collection->update([
                'created_by_id' => $request->created_by['id']
            ]);
        }

        // conditional update slug
        if ($this->authUser->can('edit collection slug') && $request->slug != $collection->slug) {
            $newSlug = Str::slug($request->slug);
            $collection->update([
                'slug' => $collection->generateUniqueSlug($newSlug)
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
