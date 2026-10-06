@extends('layouts.admin')

@section('title', trans_db('admin.plugins'))

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ trans_db('admin.plugins') }}</h1>

        <form action="{{ route('admin.plugins.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <label class="bg-indigo-600 text-white px-4 py-2 rounded cursor-pointer hover:bg-indigo-700">
                {{ trans_db('admin.upload_plugin') }}
                <input type="file" name="plugin_zip" accept=".zip" class="hidden" onchange="this.form.submit()">
            </label>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-3">{{ trans_db('admin.name') }}</th>
                    <th class="text-left px-4 py-3">{{ trans_db('admin.description') }}</th>
                    <th class="text-left px-4 py-3">{{ trans_db('admin.version') }}</th>
                    <th class="text-left px-4 py-3">{{ trans_db('admin.author') }}</th>
                    <th class="text-left px-4 py-3">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-3">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($plugins as $name => $plugin)
                    <tr class="{{ $plugin['enabled'] ? '' : 'opacity-60' }}">
                        <td class="px-4 py-3 font-semibold">{{ $plugin['name'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($plugin['description'] ?? '', 80) }}</td>
                        <td class="px-4 py-3">{{ $plugin['version'] }}</td>
                        <td class="px-4 py-3">{{ $plugin['author'] }}</td>
                        <td class="px-4 py-3">
                            @if($plugin['enabled'])
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">{{ trans_db('admin.enabled') }}</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">{{ trans_db('admin.disabled') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                @if($plugin['enabled'])
                                    <form action="{{ route('admin.plugins.disable', $name) }}" method="POST">
                                        @csrf
                                        <button class="text-yellow-600 hover:underline text-sm">{{ trans_db('admin.disable') }}</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.plugins.enable', $name) }}" method="POST">
                                        @csrf
                                        <button class="text-green-600 hover:underline text-sm">{{ trans_db('admin.enable') }}</button>
                                    </form>
                                    <form action="{{ route('admin.plugins.destroy', $name) }}" method="POST" onsubmit="return confirm('{{ trans_db('admin.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline text-sm">{{ trans_db('admin.delete') }}</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">{{ trans_db('admin.no_plugins') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded p-4 text-sm text-blue-800">
        <strong>{{ trans_db('admin.plugin_zip_help_title') }}</strong>
        <p class="mt-1">{{ trans_db('admin.plugin_zip_help') }}</p>
        <code class="block mt-2 bg-blue-100 p-2 rounded text-xs">{"name": "MyPlugin", "version": "1.0.0", "author": "You", "description": "...", "provider": "Plugins\\MyPlugin\\MyPluginServiceProvider"}</code>
    </div>
@endsection
