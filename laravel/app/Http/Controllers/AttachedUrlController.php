<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttachedUrlResource;
use App\Models\Recipe;
use App\Models\AttachedUrl;
use Illuminate\Http\Request;

class AttachedUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
             'attached_urls' => 'required|array|min:1',
             'attached_urls.*.url' => 'required|url:http,https',
             'recipe_id' => 'required|exists:recipes,id',
         ]);

        $recipe = Recipe::find($request->input('recipe_id'));
        $newAttachments = [];

        $request->collect('attached_urls')->each(function ($attachedUrl) use (&$newAttachments) {
            $new = AttachedUrl::create([
                'url' => $attachedUrl['url']
            ]);
            $newAttachments[] = $new;
        });

        $recipe->attached_urls()->saveMany($newAttachments);

        return AttachedUrlResource::collection($newAttachments);
    }

    /**
     * Display the specified resource.
     */
    public function show(AttachedUrl $attachedUrl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttachedUrl $attachedUrl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttachedUrl $attachedUrl)
    {
        //
    }
}
