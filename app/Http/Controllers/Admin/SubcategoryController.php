<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with(['category','type'])->paginate(15);
        return Inertia::render('Admin/Subcategories/Index', [
            'subcategories' => $subcategories,
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $types = Type::all();
        return Inertia::render('Admin/Subcategories/Create', [
            'categories' => $categories,
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Subcategory::create($data);
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory created.');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();
        $types = Type::all();
        return Inertia::render('Admin/Subcategories/Edit', [
            'subcategory' => $subcategory,
            'categories' => $categories,
            'types' => $types,
        ]);
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $subcategory->update($data);
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory updated.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('admin.subcategories.index')->with('success', 'Subcategory deleted.');
    }

    // AJAX method for item forms
    public function byCategory($categoryId)
    {
        $subcategories = \App\Services\StaticDataService::getSubcategoriesByCategory($categoryId);
        $formatted = $subcategories->map(function($sub) {
            return ['id' => $sub->id, 'name' => $sub->name];
        });
        return response()->json($formatted);
    }
}
