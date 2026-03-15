<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Basicinfo;
use App\Models\Category;

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

        view()->composer('*', function ($view) {
            $basicinfo = Basicinfo::first();

            $view->with('basicinfo', $basicinfo);
        });

        View()->composer('webview.partials.header', function ($view) {

            $categories = Category::where('status', 'Active')->select('id', 'category_name', 'slug')->get()->reverse();

            $view->with([
                'categories' => $categories,
            ]);
        });
    }
}
