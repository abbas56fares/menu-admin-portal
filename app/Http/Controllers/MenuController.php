<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index($brand = null)
    {
        // If no brand specified, get the first category
        if (!$brand) {
            $defaultCategory = Category::firstOrFail();
            $brand = $defaultCategory->slug;
        }
        
        // Cache menu data for 1 hour to reduce database queries
        $cacheKey = "menu_data_{$brand}";
        
        $data = Cache::remember($cacheKey, 3600, function () use ($brand) {
            // find the brand with optimized eager loading
            $category = Category::where('slug', $brand)
                ->with(['subcategories' => function($query) {
                    $query->with(['type', 'items' => function($itemQuery) {
                        $itemQuery->where('is_active', true)
                                 ->select('id', 'name', 'description', 'price', 'currency', 'image', 'subcategory_id', 'category_id');
                    }]);
                }])
                ->firstOrFail();

            // We want Beverage and Dessert to be shared between brands.
            // Load subcategories for those types globally and merge them into the category's subcategories
            $sharedTypeNames = ['Beverage', 'Dessert'];
            $sharedSubcats = \App\Models\Subcategory::whereHas('type', function($q) use ($sharedTypeNames) {
                $q->whereIn('name', $sharedTypeNames);
            })->with(['type', 'items' => function($itemQuery) {
                $itemQuery->where('is_active', true)
                         ->select('id', 'name', 'description', 'price', 'currency', 'image', 'subcategory_id', 'category_id');
            }, 'category'])->get();

            // Build a collection that contains brand-specific subcategories (e.g. Food) plus the shared ones
            $brandSubcats = $category->subcategories;
            // remove any subcategories from brandSubcats that belong to shared types to avoid duplicates
            $brandSubcats = $brandSubcats->reject(function($s) use ($sharedTypeNames) {
                return in_array($s->type->name, $sharedTypeNames);
            });

            $mergedSubcats = $brandSubcats->concat($sharedSubcats->values());

            // replace the category's subcategories with merged list for the view
            $category->setRelation('subcategories', $mergedSubcats);
            
            return $category;
        });

        // Get all categories for brand switching
        $allCategories = Category::all();

        return Inertia::render('Menu', [
            'brand' => $brand,
            'category' => $data,
            'allCategories' => $allCategories,
            'types' => Type::orderBy('name')->get(['id', 'name']),
        ]);
    }
}
