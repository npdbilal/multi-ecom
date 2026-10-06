@extends('layouts.admin')

@section('title', isset($banner) ? trans_db('admin.edit') : trans_db('admin.add_new'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if(isset($banner)) @method('PUT') @endif

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.title') }} *</label>
                <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" required class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.subtitle') }}</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.image') }}</label>
                @if(isset($banner) && $banner->image)
                    <div class="my-2"><img src="{{ $banner->imageUrl() }}" alt="" class="h-24 rounded"></div>
                @endif
                <input type="file" name="image_file" accept="image/*" class="w-full border rounded px-3 py-2 mt-1">
                <div class="text-xs text-gray-400 mt-1">…or paste an image URL:</div>
                <input type="text" name="image" value="{{ old('image', $banner->image ?? '') }}" placeholder="https://…" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.link') }}</label>
                <input type="text" name="link" value="{{ old('link', $banner->link ?? '') }}" placeholder="/products" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="flex gap-6 text-sm">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}> {{ trans_db('admin.active') }}</label>
                <label class="flex items-center gap-2">{{ trans_db('admin.sort_order') }}: <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="border rounded px-2 py-1 w-20"></label>
            </div>

            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
                <a href="{{ route('admin.banners.index') }}" class="border px-6 py-2 rounded">{{ trans_db('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
