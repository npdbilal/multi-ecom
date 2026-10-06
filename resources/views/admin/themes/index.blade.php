@extends('layouts.admin')

@section('title', trans_db('admin.themes'))

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ trans_db('admin.themes') }}</h1>

        <form action="{{ route('admin.themes.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <label class="bg-indigo-600 text-white px-4 py-2 rounded cursor-pointer hover:bg-indigo-700">
                {{ trans_db('admin.upload_theme') }}
                <input type="file" name="theme_zip" accept=".zip" class="hidden" onchange="this.form.submit()">
            </label>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($themes as $slug => $theme)
            <div class="bg-white rounded-lg shadow overflow-hidden {{ $active === $slug ? 'ring-2 ring-indigo-600' : '' }}">
                <div class="h-40 bg-gray-200 flex items-center justify-center overflow-hidden">
                    @if(!empty($theme['screenshot']) && file_exists(base_path('themes/'.$slug.'/'.$theme['screenshot'])))
                        <img src="{{ asset('themes/'.$slug.'/'.$theme['screenshot']) }}" alt="{{ $theme['name'] }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-400 text-4xl">🎨</span>
                    @endif
                </div>

                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg">{{ $theme['name'] }}</h3>
                            <p class="text-sm text-gray-500">v{{ $theme['version'] ?? '1.0.0' }} · {{ $theme['author'] ?? 'Unknown' }}</p>
                        </div>
                        @if($active === $slug)
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">{{ trans_db('admin.active') }}</span>
                        @endif
                    </div>

                    @if(!empty($theme['description']))
                        <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit($theme['description'], 100) }}</p>
                    @endif

                    <div class="flex gap-2 mt-4">
                        @if($active !== $slug)
                            <form action="{{ route('admin.themes.activate', $slug) }}" method="POST">
                                @csrf
                                <button class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">{{ trans_db('admin.activate') }}</button>
                            </form>
                            <form action="{{ route('admin.themes.destroy', $slug) }}" method="POST" onsubmit="return confirm('{{ trans_db('admin.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm hover:bg-red-200">{{ trans_db('admin.delete') }}</button>
                            </form>
                        @else
                            <span class="text-sm text-gray-500">{{ trans_db('admin.current_theme') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded p-4 text-sm text-blue-800">
        <strong>{{ trans_db('admin.theme_zip_help_title') }}</strong>
        <p class="mt-1">{{ trans_db('admin.theme_zip_help') }}</p>
        <code class="block mt-2 bg-blue-100 p-2 rounded text-xs">{"name": "My Theme", "slug": "my-theme", "version": "1.0.0", "author": "You", "description": "...", "screenshot": "screenshot.png"}</code>
    </div>
@endsection
