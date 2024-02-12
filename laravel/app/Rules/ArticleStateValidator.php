<?php

namespace App\Rules;

use Closure;
use App\Models\State;
use Illuminate\Contracts\Validation\ValidationRule;

class ArticleStateValidator implements ValidationRule
{
    private $article;
    private $authUser;

    public function __construct($article, $authUser)
    {
        $this->article = $article;
        $this->authUser = $authUser;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // editor role: independent from the old/new state this is allowed
        if ($this->authUser->can('publish articles')) {
            return;
        }
        // writer role: not allowed to publish/unpublish articles
        else {
            // case1: article is published
            if ($this->article->state->key == 'publish') {
                $fail('The article is already published');
            }
            // case2: the article is not published: allow setting the state to draft/review
            else {
                $newState = State::find($value);
                if (!$newState) {
                    $fail('Invalid state id');
                }
                if ($newState->key == 'publish') {
                    $fail('You are not allowed to publish articles');
                }
            }
        }
    }
}
