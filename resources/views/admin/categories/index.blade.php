@extends('layouts.admin')

@section('title', trans_db('admin.categories'))

@section('content')
    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ {{ trans_db('admin.add_new') }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.name') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categories as $category)
                    <tr>
                        <td class="px-4 py-2 font-semibold">{{ $category->name }}<div class="text-xs text-gray-400">{{ $category->slug }}</div></td>
                        <td class="px-4 py-2">{{ $category->is_active ? trans_db('admin.active') : trans_db('admin.inactive') }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600">{{ trans_db('admin.edit') }}</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
