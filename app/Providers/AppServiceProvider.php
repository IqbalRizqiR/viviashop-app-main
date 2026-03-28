<?php

namespace App\Providers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->usePublicPath('/../../public_html');
        // $this->app->usePublicPath('/home/u875841990/domains/viviashop.com/public_html');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // URL::forceScheme('https');
        Paginator::useBootstrap();

        // Prevent N+1 queries — throws in dev, logs in production
        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!$this->app->isProduction());

        // In production, log lazy loading violations instead of crashing
        if ($this->app->isProduction()) {
            \Illuminate\Database\Eloquent\Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
                \Illuminate\Support\Facades\Log::warning("N+1 detected: {$model}::{$relation}");
            });
        }
    }
}
