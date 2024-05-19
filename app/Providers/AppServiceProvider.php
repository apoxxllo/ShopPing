<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // $this->registerPolicies();
        Paginator::useBootstrap();
        date_default_timezone_set('Asia/Shanghai');

        Gate::define('edit-product', function ($user, $product) {
            return $user->hasRole('admin') || $user->id === $product->user_id;
        });
    }
}
