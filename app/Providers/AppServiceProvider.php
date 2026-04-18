<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\Listing;
use App\Observers\BlogPostObserver;
use App\Observers\ListingObserver;
use Illuminate\Database\Eloquent\Model;
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
        Model::shouldBeStrict(! $this->app->isProduction());

        Listing::observe(ListingObserver::class);
        BlogPost::observe(BlogPostObserver::class);
    }
}
