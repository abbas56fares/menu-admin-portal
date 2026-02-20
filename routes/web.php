<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

// Public home (menu)
Route::get('/', [MenuController::class, 'index'])->name('menu.index');

// Public menu for brand
Route::get('/menu/{brand?}', [MenuController::class, 'index'])->name('menu.brand');

// Orders (public)
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// Static branch: no auth/admin routes or database-backed dashboards
