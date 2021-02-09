<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        View::composer(['admin.aromas.aromas_create', 'admin.brands.brands_create',
            'admin.products.products_create', 'admin.settings.settings',
        ], function ($view) { $view->with('userId', auth()->user()->id);
        });
    }
}
