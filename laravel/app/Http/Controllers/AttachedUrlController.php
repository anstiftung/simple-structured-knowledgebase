<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AttachedUrl;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\AttachedUrlResource;

class AttachedUrlController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attachedUrls = AttachedUrl::when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })
        ->when(!empty($request->invalid), function ($query) use ($request) {
            if ($request->boolean('invalid')) {
                $query->invalid();
            } else {
                $query->valid();
            }
        })
        ->orderBy('created_at', 'DESC')
        ->paginate();

        return AttachedUrlResource::collection($attachedUrls);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->authUser->can('create attachments')) {
            return parent::abortUnauthorized();
        }

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
    public function show(AttachedUrl $attachedUrl, Request $request)
    {
        if ($attachedUrl->trashed() && !$this->authUser?->can('list trashed attachments')) {
            abort(404);
        }

        if ($request->boolean('withArticles')) {
            // load only published articles for unauthenticated users
            $attachedUrl->load(['articles' => function ($query) {
                if (!$this->authUser) {
                    $query->published();
                }
            }]);
        }
        return new AttachedUrlResource($attachedUrl);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttachedUrl $attachedUrl)
    {
        $request->validate([
            'attached_urls' => 'required|array|min:1',
            'attached_urls.*.id' => 'required|exists:attached_urls,id',
            'attached_urls.*.title' => 'required|max:100',
            'attached_urls.*.description' => 'required|max:250',
        ]);

        $attachmentsToUpdate = [];

        // check for permissions and get attachedfiles
        $request->collect('attached_urls')->each(function ($attachedUrlFromRequest) use (&$attachmentsToUpdate) {
            $attachedUrl = AttachedUrl::findOrFail($attachedUrlFromRequest['id']);
            if (!$this->authUser->can('update attachments') || $this->authUser->id != $attachedUrl->created_by_id) {
                return parent::abortUnauthorized();
            }
            $attachmentsToUpdate[] = [$attachedUrl, $attachedUrlFromRequest];
        });

        $updatedAttachments = [];

        foreach ($attachmentsToUpdate as [$attachedUrl, $attachedUrlFromRequest]) {
            $attachedUrl->update([
                'title' => $attachedUrlFromRequest['title'],
                'description' => $attachedUrlFromRequest['description']
            ]);

            $updatedAttachments[] = $attachedUrl;
        }

        return AttachedUrlResource::collection($updatedAttachments);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, AttachedUrl $attachedUrl)
    {
        $forceDelete = $request->boolean('forceDelete', false);

        if ($forceDelete && $this->authUser->can('force delete attachments')) {
            $attachedUrl->forceDelete();
        } elseif (!$forceDelete && ($this->authUser->id == $attachedUrl->created_by_id || $this->authUser->can('delete others attachments'))) {
            $attachedUrl->delete();
        } else {
            return parent::abortUnauthorized();
        }
        return new AttachedUrlResource($attachedUrl);
    }
}
