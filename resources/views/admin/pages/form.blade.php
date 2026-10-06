@extends('layouts.admin')

@section('title', isset($page) ? trans_db('admin.edit') : trans_db('admin.add_new'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
        <form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST" class="space-y-4">
            @csrf
            @if(isset($page)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.title') }} *</label>
                    <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.slug') }}</label>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" placeholder="auto-generated" class="w-full border rounded px-3 py-2 mt-1 font-mono">
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.body') }} (HTML)</label>
                <textarea name="body" rows="10" class="w-full border rounded px-3 py-2 mt-1 font-mono text-sm">{{ old('body', $page->body ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Meta title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Meta description</label>
                    <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description ?? '') }}" class="w-full border rounded px-3 py-2 mt-1">
                </div>
            </div>

            <div class="flex gap-6 text-sm">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}> {{ trans_db('admin.active') }}</label>
                <label class="flex items-center gap-2">{{ trans_db('admin.sort_order') }}: <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" class="border rounded px-2 py-1 w-20"></label>
            </div>

            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
                <a href="{{ route('admin.pages.index') }}" class="border px-6 py-2 rounded">{{ trans_db('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
