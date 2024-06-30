<?php

use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/admins/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::get('/admins/products', [ProductCrudController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.products.index');
Route::get('/admins/products/create', [ProductCrudController::class, 'create'])->middleware(['auth', 'verified'])->name('admin.products.create');
Route::post('/admins/products', [ProductCrudController::class, 'store'])->middleware(['auth', 'verified'])->name('admin.products.store');
Route::get('/admins/products/{id}', [ProductCrudController::class, 'show'])->middleware(['auth', 'verified'])->name('admin.products.show');
Route::get('/admins/products/{id}/edit', [ProductCrudController::class, 'edit'])->middleware(['auth', 'verified'])->name('admin.products.edit');
Route::patch('/admins/products/{id}', [ProductCrudController::class, 'update'])->middleware(['auth', 'verified'])->name('admin.products.update');
Route::delete('/admins/products/{id}', [ProductCrudController::class, 'destroy'])->middleware(['auth', 'verified'])->name('admin.products.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.addToCart');
    Route::delete('/cart/remove/{productId}', [CartController::class, 'removeFromCart'])->name('cart.removeFromCart');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');

require __DIR__ . '/auth.php';