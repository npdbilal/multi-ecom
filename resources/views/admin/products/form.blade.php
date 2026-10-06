@extends('layouts.admin')

@section('title', isset($product) ? trans_db('admin.edit') : trans_db('admin.add_new'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" class="space-y-4">
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.categories') }}</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2 mt-1">
                        <option value="">—</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="w-full border rounded px-3 py-2 mt-1">
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('shop.description') }}</label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 mt-1">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.price') }}</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.price') }} (compare)</label>
                    <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price ?? '') }}" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.stock') }}</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required class="w-full border rounded px-3 py-2 mt-1">
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-600">Image URL</label>
                <input type="text" name="image" value="{{ old('image', $product->image ?? '') }}" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="flex gap-6 text-sm">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}> {{ trans_db('admin.active') }}</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}> Featured</label>
            </div>

            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
                <a href="{{ route('admin.products.index') }}" class="border px-6 py-2 rounded">{{ trans_db('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
