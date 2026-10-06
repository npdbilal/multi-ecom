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
            ->with('category', 'images', 'activeVariants')
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($qq) => $qq->where('slug', $request->category)))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->q.'%';
                $q->where(fn ($qq) => $qq->where('name', 'like', $term)->orWhere('description', 'like', $term));
            })
            ->latest()
            ->paginate(15);

        return response()->json($products);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        return response()->json($product->load('category', 'images', 'activeVariants'));
    }

    public function languages()
    {
        return response()->json(
            Language::active()->get(['code', 'name', 'native_name', 'is_rtl'])
        );
    }
}
