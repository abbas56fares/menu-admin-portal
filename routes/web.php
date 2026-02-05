<?php



use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Models\Category;
use App\Models\Item;
use App\Models\Subcategory;
use App\Models\Type;

// Public home (menu)
Route::get('/', [MenuController::class, 'index'])->name('menu.index');

// Public menu for brand
Route::get('/menu/{brand?}', [MenuController::class, 'index'])->name('menu.brand');

// Orders (public)
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// Dashboard for authenticated users (basic)
Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard', [
        'stats' => [
            'categories' => Category::count(),
            'subcategories' => Subcategory::count(),
            'types' => Type::count(),
            'items' => Item::count(),
            'inactive' => Item::where('is_active', false)->count(),
        ],
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (Breeze default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -----------------------------
// ADMIN ROUTES (secured)
// -----------------------------
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin dashboard
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard', [
                'stats' => [
                    'categories' => Category::count(),
                    'subcategories' => Subcategory::count(),
                    'types' => Type::count(),
                    'items' => Item::count(),
                    'inactive' => Item::where('is_active', false)->count(),
                ],
            ]);
        })->name('dashboard');

        // AJAX route for subcategories
        Route::get('/ajax/subcategories/{category}', [SubcategoryController::class, 'byCategory'])
            ->name('ajax.subcategories');

        // CRUD resources
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('types', TypeController::class)->except(['show']);
        Route::resource('subcategories', SubcategoryController::class)->except(['show']);
        Route::resource('items', ItemController::class)->except(['show']);
    });

require __DIR__ . '/auth.php';
