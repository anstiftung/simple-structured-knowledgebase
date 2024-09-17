<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AttachedUrl;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\AttachedUrlResource;
use Illuminate\Support\Facades\Gate;

/**
 * @group Attachments
 */

class AttachedUrlController extends BaseController
{
    /**
     * AttachedUrl Listing
     *
     * @queryParam created_by_id int only return AttachedUrls created by the defined id
     * @queryParam invalid boolean if true, return only invalid (metadata not completely filled) AttachedUrls
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
     * AttachedUrl Save
     *
     * @bodyParam attached_urls object required List of urls to attach to an **article_id**
     * @bodyParam attached_urls.url string required a valid URL prefixed with __http://__  or __https://__
     * @bodyParam article_id object The ID of the article this urls should be attached to
     *
     * @authenticated
     */
    public function store(Request $request)
    {
        Gate::authorize('create', AttachedUrl::class);

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
     * AttachedUrl Details
     *
     * @urlParam attached_url_id int required
     * @bodyParam withArticles boolean also return all associated articles
     */
    public function show(AttachedUrl $attachedUrl, Request $request)
    {
        Gate::authorize('view', $attachedUrl);

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
     * AttachedUrl Update
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
            $attachedUrl = AttachedUrl::find($attachedUrlFromRequest['id']);

            Gate::authorize('update', $attachedUrl);

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
     * AttachedUrl Remove
     *
     * @bodyParam forceDelete boolean if true, AttachedFile will be deleted permanently
     */
    public function destroy(Request $request, AttachedUrl $attachedUrl)
    {
        $forceDelete = $request->boolean('forceDelete', false);

        if ($forceDelete === true) {
            Gate::authorize('forceDelete', $attachedUrl);
            $attachedUrl->forceDelete();
        } else {
            Gate::authorize('delete', $attachedUrl);
            $attachedUrl->delete();
        }

        return new AttachedUrlResource($attachedUrl);
    }
}
