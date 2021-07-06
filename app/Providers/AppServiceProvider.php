<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        Paginator::useBootstrap();

        JsonResource::withoutWrapping(); //this remove data[] wrapping for json resources

        View::composer(['admin.aromas.aromas_create', 'brands.brands_create',
            'admin.products.products_create', 'admin.settings.settings',
            'fops.fops_create', 'advs.advs_create',
        ], function ($view) { $view->with('userId', auth()->user()->id);
        });
    }
}
