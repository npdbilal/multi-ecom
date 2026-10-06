<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::active()
            ->with('category')
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($qq) => $qq->where('slug', $request->category)))
            ->latest()
            ->paginate(15);

        return response()->json($products);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return response()->json($product->load('category'));
    }

    public function languages()
    {
        return response()->json(
            Language::active()->get(['code', 'name', 'native_name', 'is_rtl'])
        );
    }
}
