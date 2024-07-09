<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('products')->get();
        $products = Product::with('category')->limit(8)->get();

        return Inertia::render('Home/Index', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}