@extends('layouts.admin')

@section('title', trans_db('admin.pages'))

@section('content')
    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.pages.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ {{ trans_db('admin.add_new') }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.title') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.slug') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($pages as $page)
                    <tr>
                        <td class="px-4 py-2 font-semibold">{{ $page->title }}</td>
                        <td class="px-4 py-2 font-mono text-gray-500">/pages/{{ $page->slug }}</td>
                        <td class="px-4 py-2">{{ $page->is_active ? trans_db('admin.active') : trans_db('admin.inactive') }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('pages.show', $page) }}" class="text-gray-600" target="_blank">👁</a>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-indigo-600">{{ trans_db('admin.edit') }}</a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">—</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $pages->links() }}</div>
@endsection
