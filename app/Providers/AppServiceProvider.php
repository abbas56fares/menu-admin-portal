<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\Category;
use App\Models\Subcategory;
use App\Observers\ItemObserver;
use App\Observers\CategoryObserver;
use App\Observers\SubcategoryObserver;

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
        // Prevent lazy loading in production to catch N+1 queries
        Model::preventLazyLoading(!app()->isProduction());
        
        // Enable strict mode to catch errors early
        Model::shouldBeStrict(!app()->isProduction());
        
        // Register observers to automatically clear cache when models change
        Item::observe(ItemObserver::class);
        Category::observe(CategoryObserver::class);
        Subcategory::observe(SubcategoryObserver::class);
    }
}
