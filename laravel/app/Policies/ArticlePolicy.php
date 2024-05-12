<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Article $article): Response
    {
        if ($article->state->key == 'publish') {
            return Response::allow();
        }
        // logged in users are allowed to view unpublished articles
        if ($user !== null) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->can('add articles')) {
            return Response::allow();
        }

        Response::deny('Du darfst keine Beiträge erstellen.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): Response
    {
        $denyResponse = Response::deny('Du darfst diesen Beitrag nicht bearbeiten.');

        if ($user->can('update others articles')) {
            return Response::allow();
        }

        if ($user->can('update own articles')) {
            return $user->id == $article->created_by_id
                ? Response::allow()
                : $denyResponse;
        }

        return $denyResponse;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): Response
    {
        $denyResponse = Response::deny('Du darfst diesen Beitrag nicht löschen.');

        if ($user->can('delete others articles')) {
            return Response::allow();
        }

        // writers are allowed to delete their own articles when there are in draft state
        if ($user->can('delete own articles')) {
            return $user->id == $article->created_by_id && $article->state->key == 'draft'
                    ? Response::allow()
                    : $denyResponse
            ;
        }

        return $denyResponse;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Article $article): Response
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article): Response
    {
        //
    }

    public function clap(User $user, Article $article): Response
    {
        if ($user->can('clap own articles')) {
            return Response::allow();
        }

        // logged in users are allowed to clap others articles
        if ($user && $user->id != $article->created_by_id) {
            return Response::allow();
        }

        return Response::deny('Du darfst deine eigenen Artikel nicht beklatschen!');
    }
}
