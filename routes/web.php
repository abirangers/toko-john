<?php

use App\Http\Controllers\Admin\CategoryCrudController;
use App\Http\Controllers\Admin\ClassCrudController;
use App\Http\Controllers\Admin\MajorCrudController;
use App\Http\Controllers\Admin\MediaCrudController;
use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\Admin\UserCrudController;
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

Route::get('/admin/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('media', [MediaCrudController::class, 'index'])->name('admin.media.index');
    Route::post('media', [MediaCrudController::class, 'store'])->name('admin.media.store');
    Route::delete('media/bulk-destroy', [MediaCrudController::class, 'bulkDestroy'])->name('admin.media.bulkDestroy');
    Route::delete('media/{id}', [MediaCrudController::class, 'destroy'])->middleware(['auth', 'verified'])->name('admin.media.destroy');

    Route::delete('majors/bulk-destroy', [MajorCrudController::class, 'bulkDestroy'])->name('admin.majors.bulkDestroy');
    Route::resource('majors', MajorCrudController::class)->names([
        'index' => 'admin.majors.index',
        'create' => 'admin.majors.create',
        'store' => 'admin.majors.store',
        'show' => 'admin.majors.show',
        'edit' => 'admin.majors.edit',
        'update' => 'admin.majors.update',
        'destroy' => 'admin.majors.destroy',
    ]);

    Route::delete('class/bulk-destroy', [ClassCrudController::class, 'bulkDestroy'])->name('admin.classes.bulkDestroy');
    Route::resource('classes', ClassCrudController::class)->names([
        'index' => 'admin.classes.index',
        'create' => 'admin.classes.create',
        'store' => 'admin.classes.store',
        'show' => 'admin.classes.show',
        'edit' => 'admin.classes.edit',
        'update' => 'admin.classes.update',
        'destroy' => 'admin.classes.destroy',
    ]);

    Route::delete('categories/bulk-destroy', [CategoryCrudController::class, 'bulkDestroy'])->name('admin.categories.bulkDestroy');
    Route::resource('categories', CategoryCrudController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);
    

    Route::delete('products/bulk-destroy', [ProductCrudController::class, 'bulkDestroy'])->name('admin.products.bulkDestroy');
    Route::resource('products', ProductCrudController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

    Route::delete('users/bulk-destroy', [UserCrudController::class, 'bulkDestroy'])->name('admin.users.bulkDestroy');
    Route::resource('users', UserCrudController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

});

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