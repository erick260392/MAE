<?php

namespace App\Providers;

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
        // Prevent lazy loading in development to catch N+1 query issues
        Model::preventLazyLoading(! app()->isProduction());

        // Set timezone globally
        date_default_timezone_set(config('app.timezone'));
    }
}
