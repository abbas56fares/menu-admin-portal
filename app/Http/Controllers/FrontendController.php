<?php
namespace App\Http\Controllers;
use App\Services\StaticDataService;
use Illuminate\Http\Request;

class FrontendController extends Controller {
    public function index(Request $r) {
        // Get categories with subcategories and items count
        $categories = StaticDataService::getCategories();
        $categoriesWithCounts = $categories->map(function($cat) {
            $subcategories = StaticDataService::getAllSubcategories(['items']);
            return $cat;
        });
        
        $types = StaticDataService::getTypes();
        
        // default category to show (first)
        $defaultCategory = $categories->first();
        return view('frontend.index', compact('categoriesWithCounts','types','defaultCategory'));
    }

    // Ajax - return subcategories and items for a category + type(optional)
    public function categoryData($id, Request $r) {
        $typeId = $r->query('type_id'); // optional
        
        $category = StaticDataService::getCategoryById($id);
        
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        
        // Get subcategories for this category
        $subcategories = StaticDataService::getSubcategoriesByCategory($id, ['items']);
        
        // Filter by type if specified
        if ($typeId) {
            $subcategories = $subcategories->filter(function($sub) use ($typeId) {
                return $sub->type_id == $typeId;
            })->values();
        }
        
        $category->subcategories = $subcategories;

        return response()->json($category);
    }

    // Ajax - return items by subcategory
    public function itemsBySubcategory($subcategoryId) {
        $sub = StaticDataService::getSubcategoryById($subcategoryId, ['items']);
        
        if (!$sub) {
            return response()->json(['error' => 'Subcategory not found'], 404);
        }
        
        return response()->json($sub);
    }
}
