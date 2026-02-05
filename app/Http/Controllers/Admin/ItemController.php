<?php
// namespace App\Http\Controllers\Admin;
// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Item;
// use App\Models\Category;
// use App\Models\Subcategory;
// use Illuminate\Support\Facades\Storage;

// class ItemController extends Controller {
//     public function index() {
//         $items = Item::with(['category','subcategory'])->latest()->paginate(20);
//         return view('admin.items.index', compact('items'));
//     }

//     public function create() {
//         $categories = Category::all();
//         $subcategories = Subcategory::all();
//         return view('admin.items.create', compact('categories','subcategories'));
//     }

//     public function store(Request $r) {
//         $r->validate([
//             'name'=>'required|string',
//             'category_id'=>'required|exists:categories,id',
//             'subcategory_id'=>'nullable|exists:subcategories,id',
//             'price'=>'nullable|numeric'
//         ]);

//         $data = $r->only(['name','category_id','subcategory_id','price','currency','description','is_active']);
//         if($r->hasFile('image')) {
//             $path = $r->file('image')->store('items','public');
//             $data['image'] = $path;
//         }

//         Item::create($data);
//         return redirect()->route('admin.items.index')->with('success','Item created.');
//     }

//     public function edit(Item $item) {
//         $categories = Category::all();
//         $subcategories = Subcategory::where('category_id',$item->category_id)->get();
//         return view('admin.items.edit', compact('item','categories','subcategories'));
//     }

//     public function update(Request $r, Item $item) {
//         $r->validate([
//             'name'=>'required|string',
//             'category_id'=>'required|exists:categories,id',
//             'subcategory_id'=>'nullable|exists:subcategories,id',
//             'price'=>'nullable|numeric'
//         ]);

//         $data = $r->only(['name','category_id','subcategory_id','price','currency','description','is_active']);
//         if($r->hasFile('image')) {
//             // delete old
//             if($item->image) Storage::disk('public')->delete($item->image);
//             $data['image'] = $r->file('image')->store('items','public');
//         }
//         $item->update($data);
//         return redirect()->route('admin.items.index')->with('success','Item updated.');
//     }

//     public function destroy(Item $item) {
//         if($item->image) Storage::disk('public')->delete($item->image);
//         $item->delete();
//         return back()->with('success','Item deleted.');
//     }
// }


// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use App\Models\Item;
// use App\Models\Category;
// use App\Models\Subcategory;
// use Illuminate\Http\Request;

// class ItemController extends Controller
// {
//     public function index()
//     {
//         $items = Item::with(['category','subcategory'])->get();
//         return view('admin.items.index', compact('items'));
//     }

//     public function create()
//     {
//         $categories = Category::all();
//         $subcategories = Subcategory::all();
//         return view('admin.items.create', compact('categories','subcategories'));
//     }

//     public function store(Request $request)
//     {
//         $data = $request->validate([
//             'name' => 'required|string',
//             'price' => 'required|numeric',
//             'currency' => 'required|string',
//             'description' => 'nullable|string',
//             'category_id' => 'required|exists:categories,id',
//             'subcategory_id' => 'required|exists:subcategories,id',
//             'is_active' => 'boolean',
//         ]);

//         Item::create($data);
//         return redirect()->route('admin.items.index')->with('success','Item created successfully');
//     }

//     public function edit(Item $item)
//     {
//         $categories = Category::all();
//         $subcategories = Subcategory::all();
//         return view('admin.items.edit', compact('item','categories','subcategories'));
//     }

//     public function update(Request $request, Item $item)
//     {
//         $data = $request->validate([
//             'name' => 'required|string',
//             'price' => 'required|numeric',
//             'currency' => 'required|string',
//             'description' => 'nullable|string',
//             'category_id' => 'required|exists:categories,id',
//             'subcategory_id' => 'required|exists:subcategories,id',
//             'is_active' => 'boolean',
//         ]);

//         $item->update($data);
//         return redirect()->route('admin.items.index')->with('success','Item updated successfully');
//     }

//     public function destroy(Item $item)
//     {
//         $item->delete();
//         return redirect()->route('admin.items.index')->with('success','Item deleted successfully');
//     }
// }



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageOptimizationService;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category','subcategory']);

        // search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('name', 'like', "%{$q}%");
        }

        // filter by category/subcategory/type
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('subcategory_id')) {
            // when a subcategory is selected we filter to that subcategory
            $query->where('subcategory_id', $request->subcategory_id);
        }
        if ($request->filled('type_id')) {
            // when a type is selected we filter to that type
            $query->where('type_id', $request->type_id);
        }

        // If an item is selected, bring that specific item to the top (but do not filter others)
        $itemPriorityId = $request->filled('item_id') ? (int) $request->item_id : null;
        if ($itemPriorityId) {
            // order items so the prioritized item appears first
            $query->orderByRaw('id = ? DESC', [$itemPriorityId]);
        }

        // Sorting via query string: sort and dir
        $allowed = ['name','price','created_at','category','subcategory'];
        $sort = in_array($request->get('sort'), $allowed) ? $request->get('sort') : 'name';
        $dir = strtolower($request->get('dir','asc')) === 'desc' ? 'desc' : 'asc';

        // map virtual sorts to actual columns
        switch ($sort) {
            case 'price':
                $query->orderBy('price', $dir);
                break;
            case 'created_at':
                $query->orderBy('created_at', $dir);
                break;
            case 'category':
                $query->orderBy('category_id', $dir);
                break;
            case 'subcategory':
                $query->orderBy('subcategory_id', $dir);
                break;
            case 'name':
            default:
                $query->orderBy('name', $dir);
                break;
        }

        $items = $query->paginate(10)->appends($request->all());
        $categories = Category::all();

        return Inertia::render('Admin/Items/Index', [
            'items' => $items,
            'categories' => $categories,
            'filters' => [
                'q' => $request->get('q'),
                'category_id' => $request->get('category_id'),
                'subcategory_id' => $request->get('subcategory_id'),
                'type_id' => $request->get('type_id'),
                'sort' => $request->get('sort'),
                'dir' => $request->get('dir'),
            ],
        ]);
    }

    public function create()
    {
        $categories = Category::with('subcategories')->get();
        return Inertia::render('Admin/Items/Create', [
            'categories' => $categories,
        ]);
    }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'nullable|numeric',
    //         'currency' => 'required|string|max:10',
    //         'description' => 'nullable|string',
    //         'category_id' => 'required|exists:categories,id',
    //         'subcategory_id' => 'nullable|exists:subcategories,id',
    //         'image' => 'nullable|image|max:2048',
    //         'is_active' => 'nullable|boolean',
    //     ]);

    //     // ensure boolean
    //     $data['is_active'] = $request->has('is_active');

    //     // handle image
    //     if ($request->hasFile('image')) {
    //         $path = $request->file('image')->store('items','public');
    //         $data['image'] = basename($path);
    //     }

    //     Item::create($data);

    //     return redirect()->route('admin.items.index')->with('success', 'Item created.');
    // }


    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'nullable|numeric',
        'currency' => 'required|string|max:10',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'nullable|exists:subcategories,id',
        'image' => 'nullable|image|max:10240', // Allow up to 10MB for upload, we'll optimize it
        'is_active' => 'nullable|boolean',
    ]);

    $data['is_active'] = $request->has('is_active');

    if ($request->hasFile('image')) {
        $imageService = new ImageOptimizationService();
        $data['image'] = $imageService->optimizeAndStore($request->file('image'), 'items', 800, 80);
    }

    Item::create($data);

    return redirect()->route('admin.items.index')->with('success', 'Item created with optimized image.');
}



    public function edit(Item $item)
    {
        $categories = Category::with('subcategories')->get();
        return Inertia::render('Admin/Items/Edit', [
            'item' => $item->load(['category','subcategory']),
            'categories' => $categories,
        ]);
    }

    // public function update(Request $request, Item $item)
    // {
    //     $data = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'nullable|numeric',
    //         'currency' => 'required|string|max:10',
    //         'description' => 'nullable|string',
    //         'category_id' => 'required|exists:categories,id',
    //         'subcategory_id' => 'nullable|exists:subcategories,id',
    //         'image' => 'nullable|image|max:2048',
    //         'is_active' => 'nullable|boolean',
    //     ]);

    //     $data['is_active'] = $request->has('is_active');

    //     if ($request->hasFile('image')) {
    //         // delete old image if exists
    //         if ($item->image) {
    //             Storage::disk('public')->delete('items/'.$item->image);
    //         }
    //         $path = $request->file('image')->store('items','public');
    //         $data['image'] = basename($path);
    //     }

    //     $item->update($data);

    // return redirect()->route('admin.items.index')->with('success', 'Item updated.');
    // }


    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'type_id' => 'nullable|exists:types,id',
            'image' => 'nullable|image|max:10240', // Allow up to 10MB for upload
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $imageService = new ImageOptimizationService();

            if ($item->image) {
                $imageService->delete($item->image);
            }

            $validated['image'] = $imageService->optimizeAndStore($request->file('image'), 'items', 800, 80);
        }

        $item->update($validated);

        return redirect()->route('admin.items.index')->with('success', 'Item updated with optimized image!');
    }



    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Item deleted.');
    }
}
