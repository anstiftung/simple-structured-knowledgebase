<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(600)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('claps', function (Request $request) {
            // it is safe to assume that the parameter `article` is present because the RateLimitter is only used on the article.clap route
            $slug = $request->route()->parameter('article');
            // it is safe to assume $request->user()->id is present because the clap route is protected anyway
            $cacheKey = $request->user()->id . $slug;

            return Limit::perDay(100)->by($cacheKey)->response(function (Request $request, array $headers) {
                return response()->json(['message' => 'Genug claps für heute…'], 429, $headers);
            });
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
