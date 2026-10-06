<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(6);

        $product = Product::create($data);
        $this->syncImages($request, $product);
        $this->syncVariants($request, $product);

        return redirect()->route('admin.products.index')->with('success', trans_db('admin.saved'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('images', 'variants');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($this->validated($request));
        $this->syncImages($request, $product);
        $this->syncVariants($request, $product);

        return redirect()->route('admin.products.index')->with('success', trans_db('admin.saved'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', trans_db('admin.deleted'));
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
            'sku' => 'nullable|string|max:100|unique:products,sku,'.($request->route('product')?->id ?? 'NULL'),
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:product_images,id',
            'primary_image' => 'nullable|exists:product_images,id',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.name' => 'required_with:variants|string|max:255',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.is_active' => 'nullable|boolean',
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    /**
     * Store newly uploaded gallery images, handle deletions and the primary flag.
     */
    protected function syncImages(Request $request, Product $product): void
    {
        if ($request->filled('delete_images')) {
            $product->images()->whereIn('id', $request->input('delete_images'))->delete();
        }

        if ($request->hasFile('images')) {
            $sortOrder = (int) ($product->images()->max('sort_order') ?? -1);
            foreach ($request->file('images') as $file) {
                $sortOrder++;
                $product->images()->create([
                    'path' => $file->store('media/products', 'public'),
                    'sort_order' => $sortOrder,
                    'is_primary' => $product->images()->count() === 0 && $sortOrder === 0,
                ]);
            }
        }

        if ($request->filled('primary_image')) {
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('id', $request->input('primary_image'))->update(['is_primary' => true]);
        }
    }

    /**
     * Sync the variant rows submitted from the product form.
     */
    protected function syncVariants(Request $request, Product $product): void
    {
        $rows = collect($request->input('variants', []))
            ->filter(fn ($v) => ! empty($v['name']));

        $keptIds = [];

        foreach ($rows as $i => $row) {
            $data = [
                'name' => $row['name'],
                'sku' => $row['sku'] ?? null,
                'price' => $row['price'] !== '' && $row['price'] !== null ? $row['price'] : null,
                'stock' => (int) ($row['stock'] ?? 0),
                'sort_order' => $i,
                'is_active' => ! empty($row['is_active']),
            ];

            if (! empty($row['id']) && $product->variants()->where('id', $row['id'])->exists()) {
                $product->variants()->where('id', $row['id'])->update($data);
                $keptIds[] = $row['id'];
            } else {
                $keptIds[] = $product->variants()->create($data)->id;
            }
        }

        $product->variants()->whereNotIn('id', $keptIds)->delete();
    }
}
