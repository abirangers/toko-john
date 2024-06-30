<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            })->get();
        } else {
            $products = Product::with('category')->get();
        }

        return Inertia::render("Dashboard/Product/Index", [
            "products" => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render("Dashboard/Product/Create", [
            "categories" => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create($request->all());

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();
        return Inertia::render("Dashboard/Product/Create", [
            "product" => $product,
            "categories" => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info("Update Product Request", $request->all());
        dd($request->all());
        // $product = Product::find($id);
        // if (!$product) {
            // return redirect()->route('admin.products.index')->with('error', 'Product not found');
        // }
        // $product->update($request->all());
        // return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
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
}