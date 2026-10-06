@extends('layouts.admin')

@section('title', trans_db('admin.add_new'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-3xl">
        <form action="{{ route('admin.translations.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.group') }}</label>
                    <input type="text" name="group" list="groups" required class="w-full border rounded px-3 py-2 mt-1 font-mono">
                    <datalist id="groups">@foreach($groups as $g)<option value="{{ $g }}">@endforeach</datalist>
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.key') }}</label>
                    <input type="text" name="key" required class="w-full border rounded px-3 py-2 mt-1 font-mono">
                </div>
            </div>

            @foreach($languages as $lang)
                <div>
                    <label class="text-sm text-gray-600">{{ $lang->name }} ({{ $lang->code }})</label>
                    <input type="text" name="values[{{ $lang->code }}]" class="w-full border rounded px-3 py-2 mt-1" dir="{{ $lang->is_rtl ? 'rtl' : 'ltr' }}">
                </div>
            @endforeach

            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
                <a href="{{ route('admin.translations.index') }}" class="border px-6 py-2 rounded">{{ trans_db('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
