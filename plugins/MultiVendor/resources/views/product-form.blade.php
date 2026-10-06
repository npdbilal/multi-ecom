@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold tracking-tight mb-6">{{ isset($product) ? 'Edit Product' : 'Add Product' }}</h1>

    <form action="{{ isset($product) ? route('vendor.products.update', $product) : route('vendor.products.store') }}" method="POST" class="space-y-4">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required placeholder="Product name"
               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        <select name="category_id" class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm">
            <option value="">Select category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <textarea name="short_description" rows="2" placeholder="Short description"
                  class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm">{{ old('short_description', $product->short_description ?? '') }}</textarea>
        <textarea name="description" rows="4" placeholder="Full description"
                  class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm">{{ old('description', $product->description ?? '') }}</textarea>

        <div class="grid grid-cols-3 gap-4">
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required placeholder="Price"
                   class="border border-neutral-300 rounded-xl px-4 py-3 text-sm">
            <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" placeholder="Compare price"
                   class="border border-neutral-300 rounded-xl px-4 py-3 text-sm">
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required placeholder="Stock"
                   class="border border-neutral-300 rounded-xl px-4 py-3 text-sm">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" placeholder="SKU"
                   class="border border-neutral-300 rounded-xl px-4 py-3 text-sm">
            <input type="text" name="image" value="{{ old('image', $product->image ?? '') }}" placeholder="Image URL"
                   class="border border-neutral-300 rounded-xl px-4 py-3 text-sm">
        </div>

        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} class="accent-neutral-900"> Active</label>

        <button class="w-full bg-neutral-900 text-white py-3.5 rounded-full font-medium">Save Product</button>
    </form>
</div>
@endsection
