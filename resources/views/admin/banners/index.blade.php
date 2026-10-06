@extends('layouts.admin')

@section('title', trans_db('admin.banners'))

@section('content')
    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.banners.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ {{ trans_db('admin.add_new') }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.image') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.title') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.link') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.sort_order') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($banners as $banner)
                    <tr>
                        <td class="px-4 py-2"><img src="{{ $banner->imageUrl() }}" alt="" class="h-12 w-24 object-cover rounded"></td>
                        <td class="px-4 py-2 font-semibold">{{ $banner->title }}<div class="text-xs text-gray-400 font-normal">{{ $banner->subtitle }}</div></td>
                        <td class="px-4 py-2 text-gray-500 truncate max-w-[200px]">{{ $banner->link ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $banner->sort_order }}</td>
                        <td class="px-4 py-2">{{ $banner->is_active ? trans_db('admin.active') : trans_db('admin.inactive') }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="text-indigo-600">{{ trans_db('admin.edit') }}</a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">—</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $banners->links() }}</div>
@endsection
