<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\BulkDestroyRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Spatie\Permission\Middleware\PermissionMiddleware;

class CategoryCrudController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using('find-all-categories'), only: ['index']),
            new Middleware(PermissionMiddleware::using('create-category'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('update-category'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('delete-category'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('bulk-delete-categories'), only: ['bulkDestroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('Admin/Category/Index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Category/Manage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $category = Category::findOrFail($id);
        return Inertia::render('Admin/Category/Show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $category = Category::findOrFail($id);
        return Inertia::render('Admin/Category/Manage', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->slug = null;
        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $category = Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }

    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->validated('ids');
        Category::whereIn('id', $ids)->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Bulk categories deleted successfully');
    }
}