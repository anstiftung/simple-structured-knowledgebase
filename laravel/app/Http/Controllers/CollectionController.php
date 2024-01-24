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
        $collections = Collection::when($request->featured == true, function ($query) {
            return $query->featured()->orderBy('order', 'ASC');
        })->paginate();

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
         ]);

        $newCollection = Collection::create([
           'title' => $request->title,
           'slug' => Str::slug($request->title),
           'description' => $request->description,
           'content' => $request->content
        ]);

        return new CollectionResource($newCollection);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        $collection->load(['articles']);
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
         ]);

        $collection->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
        ]);

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
