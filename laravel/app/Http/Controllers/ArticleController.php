<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\ArticleStateValidator;
use App\Http\Resources\ArticleResource;
use App\Http\Controllers\BaseController;

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
        ->when(empty($this->user), function ($query) {
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
        if (!$this->user->can('add articles')) {
            return parent::abortUnauthorized();
        }

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
        if (!$this->user && $article->state->key != 'publish') {
            // @todo: this is not the default not found response as generated by the Route Model binding functionality
            abort(404);
        }

        $article->load(['comments', 'attached_files', 'attached_urls', 'collections' => function ($query) {
            if (!$this->user) {
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
        if (!$this->user->can('edit articles')) {
            return parent::abortUnauthorized();
        }

        if($this->user->id !== $article->created_by_id && !$this->user->can('update others articles')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:1000',
            'content' => 'present|string|nullable',
            'state.id' => ['exists:states,id', new ArticleStateValidator($article, $this->user)],
            'created_by.id' => 'exists:users,id'
        ]);

        $article->update([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'state_id' => $request->state['id']
        ]);

        // conditional update article creator
        if ($this->user->can('edit article creator')) {
            $article->update([
                'created_by_id' => $request->created_by['id']
            ]);
        }

        // conditional update approved
        if ($this->user->can('approve content')) {
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
        if (!$this->user->can('delete articles')) {
            return parent::abortUnauthorized();
        }

        $article->delete();
        return new ArticleResource($article);
    }

    public function clap(Article $article)
    {
        // only editors are allowed to clap their own articles
        if($this->user->id == $article->created_by_id && !$this->user->can('clap own articles')) {
            return parent::abortUnauthorized('Du darfst deine eigenen Artikel nicht beklatschen!');
        }

        $article->increment('claps');

        return new ArticleResource($article);
    }
}
