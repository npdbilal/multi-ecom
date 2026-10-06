@extends('layouts.admin')

@section('title', isset($language) ? trans_db('admin.edit') : trans_db('admin.add_new'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ isset($language) ? route('admin.languages.update', $language) : route('admin.languages.store') }}" method="POST" class="space-y-4">
            @csrf
            @if(isset($language)) @method('PUT') @endif

            @if(! isset($language))
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.code') }} (e.g. en, ur, es)</label>
                    <input type="text" name="code" value="{{ old('code') }}" required maxlength="10" class="w-full border rounded px-3 py-2 mt-1 font-mono">
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $language->name ?? '') }}" required class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.native_name') }}</label>
                    <input type="text" name="native_name" value="{{ old('native_name', $language->native_name ?? '') }}" class="w-full border rounded px-3 py-2 mt-1">
                </div>
            </div>

            <div class="flex gap-6 text-sm">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_rtl" value="1" {{ old('is_rtl', $language->is_rtl ?? false) ? 'checked' : '' }}> {{ trans_db('admin.rtl') }}</label>
                @if(isset($language) && ! $language->is_default)
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $language->is_active) ? 'checked' : '' }}> {{ trans_db('admin.active') }}</label>
                @endif
            </div>

            <div>
                <label class="text-sm text-gray-600">Sort order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $language->sort_order ?? 0) }}" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
                <a href="{{ route('admin.languages.index') }}" class="border px-6 py-2 rounded">{{ trans_db('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
