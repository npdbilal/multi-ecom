@extends('layouts.admin')

@section('title', trans_db('admin.themes'))

@section('content')
    <div class="grid md:grid-cols-3 gap-6">
        @foreach($themes as $theme)
            <div class="bg-white rounded-lg shadow overflow-hidden {{ $theme['active'] ? 'ring-2 ring-indigo-600' : '' }}">
                <div class="aspect-video bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-4xl">🎨</div>
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold">{{ $theme['name'] }}</h3>
                        @if($theme['active'])
                            <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded">{{ trans_db('admin.active') }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $theme['description'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ trans_db('admin.version') }}: {{ $theme['version'] }} · {{ trans_db('admin.author') }}: {{ $theme['author'] }}</p>
                    @if(! $theme['active'])
                        <form action="{{ route('admin.themes.activate', $theme['slug']) }}" method="POST" class="mt-3">@csrf
                            <button class="bg-indigo-600 text-white px-4 py-1.5 rounded text-sm">{{ trans_db('admin.activate') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
