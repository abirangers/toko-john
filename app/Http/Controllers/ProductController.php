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
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = Product::with('category')->where('slug', $slug)->first();

        return Inertia::render('Product/ProductDetail/Index', compact('product'));
    }
}