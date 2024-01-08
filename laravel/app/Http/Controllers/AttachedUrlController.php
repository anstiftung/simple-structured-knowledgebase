<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AttachedUrl;
use Illuminate\Http\Request;
use App\Http\Resources\AttachedUrlResource;

class AttachedUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attachedUrls = AttachedUrl::when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })->orderBy('updated_at', 'DESC')->paginate();

        return AttachedUrlResource::collection($attachedUrls);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
             'attached_urls' => 'required|array|min:1',
             'attached_urls.*.url' => 'required|url:http,https',
             'article_id' => 'exists:articles,id',
         ]);

         $newAttachments = [];

         $request->collect('attached_urls')->each(function ($attachedUrl) use (&$newAttachments) {
             $new = AttachedUrl::create([
                 'url' => $attachedUrl['url']
                ]);
                $newAttachments[] = $new;
            });

        if ($request->input('article_id')) {
            $article = Article::find($request->input('article_id'));
            $article->attached_urls()->saveMany($newAttachments);
        }

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
        $request->validate([
            'attached_urls' => 'required|array|min:1',
            'attached_urls.*.id' => 'required|exists:attached_urls,id',
            'attached_urls.*.title' => 'required|max:30',
            'attached_urls.*.description' => 'required|max:50',
        ]);

        $updatedAttachments = [];

        $request->collect('attached_urls')->each(function ($attachedUrl) use (&$updatedAttachments) {
            $updated = tap(AttachedUrl::find($attachedUrl['id']))->update([
                'title' => $attachedUrl['title'],
                'description' => $attachedUrl['description']
            ]);

            $updatedAttachments[] = $updated;
        });

        return AttachedUrlResource::collection($updatedAttachments);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttachedUrl $attachedUrl)
    {
        //
    }
}
