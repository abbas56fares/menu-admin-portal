<?php

namespace App\Http\Controllers;

use App\Services\StaticDataService;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index($brand = null)
    {
        $allCategories = StaticDataService::getCategories();
        if ($allCategories->isEmpty()) {
            return Inertia::render('Menu', [
                'brand' => null,
                'category' => null,
                'allCategories' => [],
                'types' => [],
            ]);
        }

        $defaultCategory = $allCategories->first();
        if (! $brand && $defaultCategory) {
            $brand = $defaultCategory->slug;
        }

        // Get category data with relationships
        $category = StaticDataService::getCategoryBySlug($brand, ['subcategories']);
        
        if (! $category && $defaultCategory) {
            $category = StaticDataService::getCategoryBySlug($defaultCategory->slug, ['subcategories']);
        }

        if ($category) {
            // Load subcategories with their type and items
            $brandSubcats = StaticDataService::getSubcategoriesByCategory($category->id, ['type', 'items']);
            
            // We want Beverage and Dessert to be shared between brands.
            // Load subcategories for those types globally and merge them
            $sharedTypeNames = ['Beverage', 'Dessert'];
            $sharedSubcats = StaticDataService::getSubcategoriesByTypeNames($sharedTypeNames, ['type', 'items', 'category']);
            
            // Remove any subcategories from brandSubcats that belong to shared types to avoid duplicates
            $brandSubcats = $brandSubcats->reject(function($s) use ($sharedTypeNames) {
                return in_array($s->type->name, $sharedTypeNames);
            });
            
            // Merge brand-specific and shared subcategories
            $mergedSubcats = $brandSubcats->concat($sharedSubcats->values());
            
            // Update the category's subcategories
            $category->subcategories = $mergedSubcats;
        }

        if (! $category) {
            return Inertia::render('Menu', [
                'brand' => $brand,
                'category' => null,
                'allCategories' => $allCategories,
                'types' => [],
            ]);
        }

        return Inertia::render('Menu', [
            'brand' => $brand,
            'category' => $category,
            'allCategories' => $allCategories,
            'types' => StaticDataService::getTypes(),
        ]);
    }
}
