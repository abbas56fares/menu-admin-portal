<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller {
    public function index(Request $r) {
        // Cache frontend data for 30 minutes
        $categories = Cache::remember('frontend_categories', 1800, function() {
            return Category::with(['subcategories'=>fn($q)=>$q->withCount('items')])->get();
        });
        
        $types = Cache::remember('frontend_types', 1800, function() {
            return Type::all();
        });
        
        // default category to show (first)
        $defaultCategory = $categories->first();
        return view('frontend.index', compact('categories','types','defaultCategory'));
    }

    // Ajax - return subcategories and items for a category + type(optional)
    public function categoryData($id, Request $r) {
        $typeId = $r->query('type_id'); // optional
        $cacheKey = "category_data_{$id}_type_" . ($typeId ?? 'all');
        
        $category = Cache::remember($cacheKey, 1800, function() use ($id, $typeId) {
            return Category::with(['subcategories'=>function($q) use ($typeId){
                if($typeId) $q->where('type_id',$typeId);
                $q->with(['items'=>function($iq){ 
                    $iq->where('is_active',true)
                       ->select('id', 'name', 'description', 'price', 'currency', 'image', 'subcategory_id', 'category_id');
                }]);
            }])->findOrFail($id);
        });

        return response()->json($category);
    }

    // Ajax - return items by subcategory
    public function itemsBySubcategory($subcategoryId) {
        $cacheKey = "subcategory_items_{$subcategoryId}";
        
        $sub = Cache::remember($cacheKey, 1800, function() use ($subcategoryId) {
            return \App\Models\Subcategory::with(['items' => function($query) {
                $query->where('is_active', true)
                      ->select('id', 'name', 'description', 'price', 'currency', 'image', 'subcategory_id', 'category_id');
            }])->findOrFail($subcategoryId);
        });
        
        return response()->json($sub);
    }
}
