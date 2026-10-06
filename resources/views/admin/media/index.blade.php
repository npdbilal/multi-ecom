@extends('layouts.admin')

@section('title', trans_db('admin.media'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="font-semibold mb-3">⬆️ {{ trans_db('admin.upload') }}</h3>
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="flex gap-3 items-center">
            @csrf
            <input type="file" name="files[]" multiple accept="image/*" required class="border rounded px-3 py-2">
            <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.upload') }}</button>
        </form>
        <p class="text-xs text-gray-400 mt-2">Images up to 5 MB each. Files are stored in <code>storage/app/public/media</code> (run <code>php artisan storage:link</code>).</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-semibold mb-4">🗃️ {{ trans_db('admin.media') }} ({{ $files->count() }})</h3>

        @if($files->isEmpty())
            <p class="text-gray-400 text-sm">No files yet.</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($files as $file)
                    <div class="border rounded-lg overflow-hidden">
                        <img src="{{ $file['url'] }}" alt="" class="w-full h-28 object-cover">
                        <div class="p-2 text-xs">
                            <div class="truncate font-mono" title="{{ $file['name'] }}">{{ $file['name'] }}</div>
                            <div class="text-gray-400">{{ round($file['size'] / 1024) }} KB</div>
                            <div class="flex gap-2 mt-2">
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $file['url'] }}'); this.textContent='✓'" class="text-indigo-600">Copy URL</button>
                                <form action="{{ route('admin.media.destroy', ['file' => $file['path']]) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                    <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                                </form>
                            </div>
                            <form action="{{ route('admin.media.attach') }}" method="POST" class="mt-2 flex gap-1">
                                @csrf
                                <input type="hidden" name="path" value="{{ $file['path'] }}">
                                <input type="number" name="product_id" placeholder="Product ID" required class="border rounded px-2 py-1 w-full">
                                <button class="text-indigo-600 whitespace-nowrap" title="{{ trans_db('admin.attach_to_product') }}">+📦</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
