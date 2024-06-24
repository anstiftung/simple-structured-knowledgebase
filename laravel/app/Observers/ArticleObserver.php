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
        if ($article->wasChanged('state_id')) {
            $newState = State::findOrFail($article->state_id);
            // maybe helpfull in the future: $oldState = State::findOrFail($article->getOriginal('state_id'));

            if ($newState->key == 'publish') {
                Mail::to($article->created_by->email)->queue(new ArticlePublished($article));
            }

            if ($newState->key == 'review') {
                $notification_recipients  = config('cowiki.system_notifcation_email', []);
                foreach ($notification_recipients as $recipient_mail) {
                    Mail::to($recipient_mail)->queue(new ArticleReview($article));
                }
            }
        }
    }


    /**
     * Listen to the Article saved event. Get's called on update and create
     *
     * @param  Article $article
     * @return void
     */
    public function saved(Article $article)
    {
        $this->syncAttachmentsFromContent($article);
    }

    private function syncAttachmentsFromContent(Article &$article)
    {
        $urls = [];
        $files = [];

        $matches = [];
        $regex = '/<item-link[^>]*>(.*?)<\/item-link>/s';

        preg_match_all($regex, $article->content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $attributes = [];
            preg_match_all('/(\w+)="([^"]*)"/', $match[0], $attrMatches, PREG_SET_ORDER);
            foreach ($attrMatches as $attrMatch) {
                $attributes[$attrMatch[1]] = $attrMatch[2];
            }

            if ($attributes['type'] === 'AttachedUrl') {
                $urls[] = $attributes['id'];
            }
            if ($attributes['type'] === 'AttachedFile') {
                $files[] = $attributes['id'];
            }
        }

        $article->attached_urls()->sync($urls);
        $article->attached_files()->sync($files);
    }
}
