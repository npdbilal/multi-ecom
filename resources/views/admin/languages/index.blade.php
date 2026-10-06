@extends('layouts.admin')

@section('title', trans_db('admin.languages'))

@section('content')
    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.languages.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ {{ trans_db('admin.add_new') }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.code') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.name') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.native_name') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.rtl') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($languages as $language)
                    <tr>
                        <td class="px-4 py-2 font-mono font-bold">{{ $language->code }}</td>
                        <td class="px-4 py-2">{{ $language->name }}</td>
                        <td class="px-4 py-2">{{ $language->native_name }}</td>
                        <td class="px-4 py-2">{{ $language->is_rtl ? '✓' : '—' }}</td>
                        <td class="px-4 py-2">
                            @if($language->is_default)
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs">{{ trans_db('admin.default') }}</span>
                            @elseif($language->is_active)
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">{{ trans_db('admin.active') }}</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-xs">{{ trans_db('admin.inactive') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 flex gap-2 flex-wrap">
                            <a href="{{ route('admin.languages.edit', $language) }}" class="text-indigo-600">{{ trans_db('admin.edit') }}</a>
                            @if(! $language->is_default)
                                <form action="{{ route('admin.languages.default', $language) }}" method="POST">@csrf
                                    <button class="text-green-600">{{ trans_db('admin.make_default') }}</button>
                                </form>
                                <form action="{{ route('admin.languages.toggle', $language) }}" method="POST">@csrf
                                    <button class="text-yellow-600">{{ $language->is_active ? trans_db('admin.disable') : trans_db('admin.enable') }}</button>
                                </form>
                                <form action="{{ route('admin.languages.destroy', $language) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                    <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
