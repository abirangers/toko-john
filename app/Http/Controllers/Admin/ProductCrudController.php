<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Common\BulkDestroyRequest;
use App\Http\Requests\Admin\Product\CreateProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ProductCrudController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using('find-all-products'), only: ['index']),
            new Middleware(PermissionMiddleware::using('create-product'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('find-product'), only: ['show']),
            new Middleware(PermissionMiddleware::using('update-product'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('delete-product'), only: ['destroy']),
            new Middleware(PermissionMiddleware::using('bulk-delete-products'), only: ['bulkDestroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoryParams = $request->query('category');

        if ($categoryParams) {
            $products = Product::with('category')->whereHas('category', function ($query) use ($categoryParams) {
                $query->where('name', $categoryParams);
            })->orderByDesc('created_at')->get();
        } else {
            $products = Product::with('category')->get();
        }

        return Inertia::render("Admin/Product/Index", [
            "products" => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render("Admin/Product/Manage", [
            "categories" => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $product = Product::create($request->validated());
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product = Product::with('category')->findOrFail($id);
        return Inertia::render("Admin/Product/Show", compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();
        return Inertia::render("Admin/Product/Manage", compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(BulkDestroyRequest $request)
    {
        $ids = $request->validated('ids');
        Product::whereIn('id', $ids)->delete();

        return redirect()->route('admin.products.index')->with('success', 'Bulk delete products successfully');
    }
}