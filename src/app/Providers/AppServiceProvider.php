<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Genre;
use App\Models\Area;

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
        view()->composer('components.header', function ($view) {
            $view->with('genres', Genre::all());
        });
    }
}
