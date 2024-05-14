<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Collection;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use App\Policies\ArticlePolicy;
use App\Observers\ArticleObserver;
use App\Policies\AttachedUrlPolicy;
use App\Policies\AttachedFilePolicy;
use App\Policies\CollectionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Article::observe(ArticleObserver::class);

        Gate::policy(Article::class, ArticlePolicy::class);
        Gate::policy(AttachedFile::class, AttachedFilePolicy::class);
        Gate::policy(AttachedUrl::class, AttachedUrlPolicy::class);
        Gate::policy(Collection::class, CollectionPolicy::class);
    }
}
