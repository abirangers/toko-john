<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductCrudController extends Controller
{
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

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        return Inertia::render("Admin/Product/Show", [
            "product" => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();
        return Inertia::render("Admin/Product/Manage", [
            "product" => $product,
            "categories" => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {

        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.products.index')->with('error', 'Product not found');
        }
        $product->update($request->validated());
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.products.index')->with('error', 'Product not found');
        }
        if ($product->image) {
            Storage::delete('public/images/products/' . $product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('admin.products.index')->with('error', 'No products selected for deletion');
        }

        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {
            if ($product->image) {
                Storage::delete('public/images/products/' . $product->image);
            }
            $product->delete();
        }

        return redirect()->route('admin.products.index')->with('success', 'Bulk delete products successfully');
    }
}