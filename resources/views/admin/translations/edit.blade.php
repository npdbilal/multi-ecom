@extends('layouts.admin')

@section('title', trans_db('admin.edit'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <p class="font-mono text-sm bg-gray-100 rounded px-3 py-2 mb-4">{{ $translation->group }}.{{ $translation->key }} <span class="text-gray-500">({{ $translation->language_code }})</span></p>

        <form action="{{ route('admin.translations.update', $translation) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-sm text-gray-600">{{ trans_db('admin.value') }}</label>
                <textarea name="value" rows="3" required class="w-full border rounded px-3 py-2 mt-1">{{ old('value', $translation->value) }}</textarea>
            </div>
            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
                <a href="{{ route('admin.translations.index') }}" class="border px-6 py-2 rounded">{{ trans_db('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
