<?php
use App\Http\Controllers\Admin\MajorCrudController;
use App\Http\Controllers\Admin\ClassCrudController;
use App\Http\Controllers\Admin\CategoryCrudController;
use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Admin\RoleCrudController;
use App\Http\Controllers\Admin\PermissionCrudController;
use App\Http\Controllers\Admin\PermissionGroupCrudController;


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
require __DIR__ . '/auth.php';