<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ArticlePublishedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        return $builder->whereHas('state', function ($query) {
            $query->where('key', 'publish');
        });
    }
}
