<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Tentative;
use App\Observers\TentativeObserver;

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
        Tentative::observe(TentativeObserver::class);
    }
}
