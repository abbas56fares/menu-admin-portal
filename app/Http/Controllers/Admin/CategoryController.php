<?php
// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Category;
// use Illuminate\Support\Str;

// class CategoryController extends Controller
// {
//     public function index(){
//         $categories = Category::latest()->paginate(15);
//         return view('admin.categories.index', compact('categories'));
//     }

//     public function create(){
//         return view('admin.categories.create');
//     }

//     public function store(Request $r){
//         $r->validate([
//             'name' => 'required|string|unique:categories,name',
//             'logo' => 'nullable|image|max:2048'
//         ]);

//         $data = ['name' => $r->name, 'slug' => Str::slug($r->name)];
//         if($r->hasFile('logo')){
//             $path = $r->file('logo')->store('logos','public');
//             $data['logo'] = $path;
//         }

//         Category::create($data);
//         return redirect()->route('admin.categories.index')->with('success','Category created.');
//     }

//     public function edit(Category $category){
//         return view('admin.categories.edit', compact('category'));
//     }

//     public function update(Request $r, Category $category){
//         $r->validate([
//             'name' => 'required|string|unique:categories,name,'.$category->id,
//             'logo' => 'nullable|image|max:2048'
//         ]);

//         $data = ['name' => $r->name, 'slug' => Str::slug($r->name)];
//         if($r->hasFile('logo')){
//             // delete old
//             if($category->logo) \Storage::disk('public')->delete($category->logo);
//             $data['logo'] = $r->file('logo')->store('logos','public');
//         }
//         $category->update($data);
//         return redirect()->route('admin.categories.index')->with('success','Category updated.');
//     }

//     public function destroy(Category $category){
//         if($category->logo) \Storage::disk('public')->delete($category->logo);
//         $category->delete();
//         return back()->with('success','Category deleted.');
//     }
// }


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\ImageOptimizationService;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Categories/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:10240',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('logo')) {
            $imageService = new ImageOptimizationService();
            $data['logo'] = $imageService->optimizeAndStore($request->file('logo'), 'categories', 400, 85);
        }

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category created with optimized logo.');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:10240',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('logo')) {
            $imageService = new ImageOptimizationService();
            
            // Delete old logo
            if ($category->logo) {
                $imageService->delete($category->logo);
            }
            
            $data['logo'] = $imageService->optimizeAndStore($request->file('logo'), 'categories', 400, 85);
        }

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated with optimized logo.');
    }

    public function destroy(Category $category)
    {
        if ($category->logo) {
            \Storage::disk('public')->delete($category->logo);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
