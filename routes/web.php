<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleCrudController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaCrudController;
use App\Http\Controllers\Admin\OrderCrudController;
use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\Admin\CategoryCrudController;
use App\Http\Controllers\Admin\PermissionCrudController;
use App\Http\Controllers\Admin\PermissionGroupCrudController;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::redirect('/', 'admin/dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('media', [MediaCrudController::class, 'index'])->name('admin.media.index');
    Route::post('media', [MediaCrudController::class, 'store'])->name('admin.media.store');
    Route::delete('media/bulk-destroy', [MediaCrudController::class, 'bulkDestroy'])->name('admin.media.bulkDestroy');
    Route::delete('media/{id}', [MediaCrudController::class, 'destroy'])->middleware(['auth', 'verified'])->name('admin.media.destroy');

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

    Route::delete('roles/bulk-destroy', [RoleCrudController::class, 'bulkDestroy'])->name('admin.roles.bulkDestroy');
    Route::resource('roles', RoleCrudController::class)->names([
        'index' => 'admin.roles.index',
        'create' => 'admin.roles.create',
        'store' => 'admin.roles.store',
        'show' => 'admin.roles.show',
        'edit' => 'admin.roles.edit',
        'update' => 'admin.roles.update',
        'destroy' => 'admin.roles.destroy',
    ]);

    Route::delete('permissions/bulk-destroy', [PermissionCrudController::class, 'bulkDestroy'])->name('admin.permissions.bulkDestroy');
    Route::resource('permissions', PermissionCrudController::class)->names([
        'index' => 'admin.permissions.index',
        'create' => 'admin.permissions.create',
        'store' => 'admin.permissions.store',
        'show' => 'admin.permissions.show',
        'edit' => 'admin.permissions.edit',
        'update' => 'admin.permissions.update',
        'destroy' => 'admin.permissions.destroy',
    ]);

    Route::delete('permission-groups/bulk-destroy', [PermissionGroupCrudController::class, 'bulkDestroy'])->name('admin.permission-groups.bulkDestroy');
    Route::resource('permission-groups', PermissionGroupCrudController::class)->names([
        'index' => 'admin.permission-groups.index',
        'create' => 'admin.permission-groups.create',
        'store' => 'admin.permission-groups.store',
        'show' => 'admin.permission-groups.show',
        'edit' => 'admin.permission-groups.edit',
        'update' => 'admin.permission-groups.update',
        'destroy' => 'admin.permission-groups.destroy',
    ]);

    Route::delete('orders/bulk-destroy', [OrderCrudController::class, 'bulkDestroy'])->name('admin.orders.bulkDestroy');
    Route::put('orders/confirm/{id}', [OrderCrudController::class, 'confirm'])->name('admin.orders.confirm');
    Route::resource('orders', OrderCrudController::class)->names([
        'index' => 'admin.orders.index',
        'create' => 'admin.orders.create',
        'store' => 'admin.orders.store',
        'show' => 'admin.orders.show',
        'edit' => 'admin.orders.edit',
        'update' => 'admin.orders.update',
        'destroy' => 'admin.orders.destroy',
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->middleware(['role:user'])->name('cart.store');
    Route::delete('/cart/{product_id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->middleware(['role:user'])->name('order.create');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->middleware(['role:user'])->name('order.show');
    Route::post('/orders', [OrderController::class, 'store'])->middleware(['role:user'])->name('order.store');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->middleware(['role:user'])->name('order.destroy');

    Route::get('/payment/{order_code}', [PaymentController::class, 'payment'])->middleware(['role:user'])->name('payment');
    Route::put('/payment/{order_code}/success', [PaymentController::class, 'paymentSuccess'])->middleware(['role:user'])->name('payment.success');
});

Route::prefix('region')->group(function () {
    Route::get('/provinces', [RegionController::class, 'provinces']);
    Route::get('/regencies/{province_id}', [RegionController::class, 'regencies']);
    Route::get('/districts/{regency_id}', [RegionController::class, 'districts']);
    Route::get('/villages/{district_id}', [RegionController::class, 'villages']);
});

require __DIR__ . '/auth.php';