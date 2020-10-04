<?php

namespace App\Providers;

use App\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $product = ProductCategory::where('parent_category_id',NULL)->with('subCategories')->get();
//       // view()->share('mainCategory', $product);
       View::share('mainCategory',$product);
           // View::share('subCategory',ProductCategory::where('parent_category_id','!=',NULL)->get());
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->request->is('api/*'))
        {
            $this->app->register('App\Providers\CustomCamelCaseJsonProvider');

        }
    }
}
