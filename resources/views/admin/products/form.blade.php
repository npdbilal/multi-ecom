@extends('layouts.admin')

@section('title', isset($product) ? trans_db('admin.edit') : trans_db('admin.add_new'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.gallery') }}</label>
                @if(isset($product) && $product->images->count())
                    <div class="grid grid-cols-4 gap-3 mt-2 mb-3">
                        @foreach($product->images as $img)
                            <div class="border rounded overflow-hidden relative">
                                <img src="{{ $img->url() }}" alt="" class="w-full h-20 object-cover">
                                <div class="p-1 text-xs flex items-center justify-between">
                                    <label class="flex items-center gap-1" title="{{ trans_db('admin.set_primary') }}">
                                        <input type="radio" name="primary_image" value="{{ $img->id }}" {{ $img->is_primary ? 'checked' : '' }}> ★
                                    </label>
                                    <label class="flex items-center gap-1 text-red-600">
                                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"> ✕
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded px-3 py-2 mt-1">
                <p class="text-xs text-gray-400 mt-1">{{ trans_db('admin.upload_images') }} — or attach from the <a href="{{ route('admin.media.index') }}" class="text-indigo-600 underline">{{ trans_db('admin.media') }}</a> library.</p>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm text-gray-600">{{ trans_db('admin.variants') }}</label>
                    <button type="button" onclick="addVariantRow()" class="text-xs bg-gray-100 border rounded px-2 py-1">+ {{ trans_db('admin.add_variant') }}</button>
                </div>
                <div id="variant-rows" class="space-y-2">
                    @foreach(old('variants', isset($product) ? $product->variants->toArray() : []) as $i => $v)
                        <div class="variant-row grid grid-cols-12 gap-2 items-center border rounded p-2">
                            <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $v['id'] ?? '' }}">
                            <input type="text" name="variants[{{ $i }}][name]" value="{{ $v['name'] ?? '' }}" placeholder="{{ trans_db('admin.variant_name') }}" class="col-span-4 border rounded px-2 py-1 text-sm">
                            <input type="text" name="variants[{{ $i }}][sku]" value="{{ $v['sku'] ?? '' }}" placeholder="SKU" class="col-span-2 border rounded px-2 py-1 text-sm">
                            <input type="number" step="0.01" name="variants[{{ $i }}][price]" value="{{ $v['price'] ?? '' }}" placeholder="Price (opt)" class="col-span-2 border rounded px-2 py-1 text-sm">
                            <input type="number" name="variants[{{ $i }}][stock]" value="{{ $v['stock'] ?? 0 }}" placeholder="Stock" class="col-span-2 border rounded px-2 py-1 text-sm">
                            <label class="col-span-1 text-xs flex items-center gap-1"><input type="checkbox" name="variants[{{ $i }}][is_active]" value="1" {{ ($v['is_active'] ?? true) ? 'checked' : '' }}> on</label>
                            <button type="button" onclick="this.closest('.variant-row').remove()" class="col-span-1 text-red-600 text-sm">✕</button>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-1">Leave price empty to use the product price. Stock is tracked per variant.</p>
            </div>

            <script>
                let variantIdx = {{ count(old('variants', isset($product) ? $product->variants : [])) }};
                function addVariantRow() {
                    const div = document.createElement('div');
                    div.className = 'variant-row grid grid-cols-12 gap-2 items-center border rounded p-2';
                    div.innerHTML = `
                        <input type="hidden" name="variants[${variantIdx}][id]" value="">
                        <input type="text" name="variants[${variantIdx}][name]" placeholder="{{ trans_db('admin.variant_name') }}" class="col-span-4 border rounded px-2 py-1 text-sm">
                        <input type="text" name="variants[${variantIdx}][sku]" placeholder="SKU" class="col-span-2 border rounded px-2 py-1 text-sm">
                        <input type="number" step="0.01" name="variants[${variantIdx}][price]" placeholder="Price (opt)" class="col-span-2 border rounded px-2 py-1 text-sm">
                        <input type="number" name="variants[${variantIdx}][stock]" value="0" placeholder="Stock" class="col-span-2 border rounded px-2 py-1 text-sm">
                        <label class="col-span-1 text-xs flex items-center gap-1"><input type="checkbox" name="variants[${variantIdx}][is_active]" value="1" checked> on</label>
                        <button type="button" onclick="this.closest('.variant-row').remove()" class="col-span-1 text-red-600 text-sm">✕</button>`;
                    document.getElementById('variant-rows').appendChild(div);
                    variantIdx++;
                }
            </script>

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
