<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Rules\ArticleStateValidator;
use App\Http\Resources\ArticleResource;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

/**
 * @group Articles
 */

class ArticleController extends BaseController
{
    /**
     * Article Listing
     *
     * This Endpoint lists all articles from the CoWiki filtered by the Query-Params
     *
     * @queryParam page int The page number which should be returned in paginated Responses.
     * @queryParam creatorId int Only get Articles created by the defined creatorId.
     * @queryParam withoutCollection bool Returns articles with no relation to any collection.
     * @queryParam withoutPagination bool disables pagination and returns all results.
     */
    public function index(Request $request)
    {
        $query = Article::when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })
        ->when(empty($this->authUser), function ($query) {
            $query->published();
        })
        ->when($request->boolean('withoutCollection'), function ($query) {
            $query->whereDoesntHave('collections');
        })
        ->orderBy('updated_at', 'DESC');

        $articles = $request->boolean('withoutPagination') ? $query->get() : $query->paginate();

        return ArticleResource::collection($articles);
    }

    /**
     * Article Save
     *
     * @authenticated
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Article::class);

        // set draft state automatically on Article creation
        $draftState = State::where('key', 'draft')->first();
        if (!$draftState) {
            return parent::abortServerError('State not found');
        }

        $request->validate([
             'title' => 'required|max:255',
             'description' => 'required|max:1000',
             'content' => 'present|string|nullable'
         ]);

        $newArticle = Article::create([
           'title' => $request->title,
           'description' => $request->description,
           'content' => $request->content,
           'state_id' => $draftState->id
        ]);

        return new ArticleResource($newArticle);
    }

    /**
     * Article Detail
     *
     * Shows all publised Articles from the CoWiki.
     *
     * @unauthenticated
     */
    public function show(Article $article)
    {
        Gate::authorize('view', $article);

        $article->load(['comments', 'attached_files', 'attached_urls', 'collections' => function ($query) {
            if (!$this->authUser) {
                $query->published();
            }
        }]);

        return new ArticleResource($article);
    }

    /**
     * Article Update
     *
     * @authenticated
     *
     * @urlParam article_id int required The ID of the article. Example: 12
     */
    public function update(Article $article, Request $request)
    {
        Gate::authorize('update', $article);

        $request->validate([
            // The articles title
            'title' => 'required|max:255',
            // The articles short description (or excerpt).
            'description' => 'required|max:1000',
            // The articles full content Markdown and some Special Tags are supported.
            'content' => 'present|string|nullable',
            // The articles state
            'state.id' => ['required|exists:states,id', new ArticleStateValidator($article, $this->authUser)],
            // The articles creator ID
            'created_by.id' => 'required|exists:users,id'
        ]);

        $article->update([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'state_id' => $request->state['id']
        ]);

        // conditional update article creator
        if ($this->authUser->can('edit article creator')) {
            $article->update([
                'created_by_id' => $request->created_by['id']
            ]);
        }

        // conditional update slug
        if ($this->authUser->can('edit article slug') && $request->slug != $article->slug) {
            $newSlug = Str::slug($request->slug);
            $article->update([
                'slug' => $article->generateUniqueSlug($newSlug)
            ]);
        }

        // conditional update approved
        if ($this->authUser->can('approve content')) {
            $article->update([
                'approved' => $request->approved
            ]);
        }

        $article->load(['attached_files', 'attached_urls']);

        return new ArticleResource($article);
    }

    /**
     * Article Remove
     *
     * Articles get soft-deleted. Only User with the right Permissions are allowed to force-delete Articles.
     *
     * @authenticated
     *
     * @bodyParam forceDelete bool force Delete the Article. Defaults to false.
     */
    public function destroy(Request $request, Article $article)
    {
        $forceDelete = $request->boolean('forceDelete', false);

        if ($forceDelete === true) {
            Gate::authorize('forceDelete', $article);
            $article->forceDelete();
        } else {
            Gate::authorize('delete', $article);
            $article->delete();
        }

        return new ArticleResource($article);
    }

    /**
     * Article Clap
     *
     * Users can leave a Clap for articles they like. If you send an authenticated Request to this Endpoint with a valid article slug, the clap count increases by one.
     *
     * @authenticated
     */
    public function clap(Article $article)
    {
        Gate::authorize('clap', $article);

        $article->increment('claps');
        return new ArticleResource($article);
    }
}
