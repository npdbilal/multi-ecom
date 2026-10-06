<?php

namespace Plugins\MultiVendor\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Plugins\MultiVendor\Models\Vendor;

class VendorDashboardController extends Controller
{
    protected function vendor(): Vendor
    {
        return request()->attributes->get('vendor')
            ?? Vendor::forUser(auth()->id());
    }

    public function index()
    {
        $vendor = $this->vendor();

        $stats = [
            'products' => $vendor->products()->count(),
            'orders' => OrderItem::where('vendor_id', $vendor->id)->distinct('order_id')->count('order_id'),
            'earnings' => $vendor->totalEarnings(),
            'pending_orders' => OrderItem::where('vendor_id', $vendor->id)
                ->whereHas('order', fn ($q) => $q->where('status', 'pending'))
                ->count(),
        ];

        $recentItems = OrderItem::where('vendor_id', $vendor->id)
            ->with('order')
            ->latest()
            ->take(8)
            ->get();

        return view('multivendor::dashboard', compact('vendor', 'stats', 'recentItems'));
    }

    public function products()
    {
        $products = $this->vendor()->products()->latest()->paginate(15);

        return view('multivendor::products', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('multivendor::product-form', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(6);
        $data['vendor_id'] = $this->vendor()->id;

        Product::create($data);

        return redirect()->route('vendor.products')->with('success', 'Product created.');
    }

    public function editProduct(Product $product)
    {
        $this->authorizeProduct($product);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('multivendor::product-form', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->authorizeProduct($product);
        $product->update($this->validated($request));

        return redirect()->route('vendor.products')->with('success', 'Product updated.');
    }

    public function orders()
    {
        $items = OrderItem::where('vendor_id', $this->vendor()->id)
            ->with('order')
            ->latest()
            ->paginate(15);

        return view('multivendor::orders', compact('items'));
    }

    // ------------------------------------------------------------------

    protected function authorizeProduct(Product $product): void
    {
        abort_unless($product->vendor_id === $this->vendor()->id, 403);
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:500',
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'is_featured' => false,
        ];
    }
}
