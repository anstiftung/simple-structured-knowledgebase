<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\ArticleStateValidator;
use App\Http\Resources\ArticleResource;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Gate;

class ArticleController extends BaseController
{
    /**
     * Display a listing of the resource.
     * Avaible params:
     * – page
     * – creatorId -> filter by creator
     * - withoutCollection -> returns only articles without any collection
     * - withoutPagination -> disables pagination
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
     * Store a newly created resource in storage.
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
           'slug' => Str::slug($request->title),
           'description' => $request->description,
           'content' => $request->content,
           'state_id' => $draftState->id
        ]);

        return new ArticleResource($newArticle);
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(Article $article, Request $request)
    {
        Gate::authorize('update', $article);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:1000',
            'content' => 'present|string|nullable',
            'state.id' => ['exists:states,id', new ArticleStateValidator($article, $this->authUser)],
            'created_by.id' => 'exists:users,id'
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
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();
        return new ArticleResource($article);
    }

    public function clap(Article $article)
    {
        Gate::authorize('clap', $article);

        $article->increment('claps');
        return new ArticleResource($article);
    }
}
