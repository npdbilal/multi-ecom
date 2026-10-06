@extends('layouts.admin')

@section('title', isset($category) ? trans_db('admin.edit') : trans_db('admin.add_new'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf
            @if(isset($category)) @method('PUT') @endif

            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full border rounded px-3 py-2 mt-1">
            </div>
            <div>
                <label class="text-sm text-gray-600">{{ trans_db('shop.description') }}</label>
                <textarea name="description" rows="2" class="w-full border rounded px-3 py-2 mt-1">{{ old('description', $category->description ?? '') }}</textarea>
            </div>
            <div>
                <label class="text-sm text-gray-600">Parent</label>
                <select name="parent_id" class="w-full border rounded px-3 py-2 mt-1">
                    <option value="">—</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}> {{ trans_db('admin.active') }}</label>

            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
                <a href="{{ route('admin.categories.index') }}" class="border px-6 py-2 rounded">{{ trans_db('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
