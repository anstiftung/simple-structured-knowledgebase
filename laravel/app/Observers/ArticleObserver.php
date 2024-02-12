<?php

namespace App\Observers;

use App\Models\User;
use App\Models\State;
use App\Models\Article;

use App\Mail\ArticleReview;
use App\Mail\ArticlePublished;
use Illuminate\Support\Facades\Mail;

class ArticleObserver
{
    /**
     * Listen to the Article updating event.
     *
     * @param  Article $article
     * @return void
     */
    public function updated(Article $article)
    {
        $dirtyAttributes = $article->getDirty();
        $originalAttributes = $article->getOriginal();

        if ($article->wasChanged('state_id')) {
            $newState = State::findOrFail($article->state_id);
            // maybe helpfull in the future: $oldState = State::findOrFail($article->getOriginal('state_id'));

            if ($newState->key == 'publish') {
                Mail::to($article->created_by->email)->queue(new ArticlePublished($article));
            }

            if ($newState->key == 'review') {
                foreach (User::role('editor')->get() as $user) {
                    Mail::to($user->email)->queue(new ArticleReview($article));
                }
            }
        }
    }
}
