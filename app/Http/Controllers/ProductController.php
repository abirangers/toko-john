<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        $categoryParams = $request->query('category');
        if ($categoryParams) {
            $products = Product::with('category')->whereHas('category', function ($query) use ($categoryParams) {
                $query->where('name', $categoryParams);
            })->get();
        } else {
            $products = Product::with('category')->get();
        }


        return Inertia::render('Product/Index', compact('products', 'categories', 'categoryParams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = Product::with('category')->where('slug', $slug)->first();

        return Inertia::render('Product/ProductDetail/Index', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}