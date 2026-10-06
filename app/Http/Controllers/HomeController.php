<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::active()->featured()->latest()->take(8)->get();
        $latest = Product::active()->latest()->take(8)->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->take(6)->get();

        return view('home', compact('featured', 'latest', 'categories'));
    }
}
