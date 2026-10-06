@extends('layouts.admin')

@section('title', trans_db('admin.plugins'))

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.name') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.version') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($plugins as $plugin)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-bold">🔌 {{ $plugin['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $plugin['description'] }} · {{ trans_db('admin.author') }}: {{ $plugin['author'] }}</div>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $plugin['version'] }}</td>
                        <td class="px-4 py-3">
                            @if($plugin['enabled'])
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">{{ trans_db('admin.enabled') }}</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-xs">{{ trans_db('admin.disabled') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($plugin['enabled'])
                                <form action="{{ route('admin.plugins.disable', $plugin['name']) }}" method="POST">@csrf
                                    <button class="text-yellow-600">{{ trans_db('admin.disable') }}</button>
                                </form>
                            @else
                                <form action="{{ route('admin.plugins.enable', $plugin['name']) }}" method="POST">@csrf
                                    <button class="text-green-600">{{ trans_db('admin.enable') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p class="text-xs text-gray-400 mt-4">Drop new plugins into <code>plugins/</code> with a <code>plugin.json</code> manifest — they appear here automatically.</p>
@endsection
