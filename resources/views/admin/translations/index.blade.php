@extends('layouts.admin')

@section('title', trans_db('admin.translations'))

@section('content')
    {{-- Missing translation report --}}
    @php $hasMissing = collect($missing)->sum() > 0; @endphp
    @if($hasMissing)
        <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 mb-4 text-sm">
            <div class="font-bold mb-2">⚠️ {{ trans_db('admin.missing_translations') }}</div>
            <div class="flex flex-wrap gap-4">
                @foreach($missing as $code => $count)
                    @if($count > 0)
                        <form action="{{ route('admin.translations.sync-missing') }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="language" value="{{ $code }}">
                            <span><strong>{{ $code }}</strong>: {{ $count }} missing</span>
                            <button class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">{{ trans_db('admin.sync_missing') }}</button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <div class="flex flex-wrap gap-3 mb-4 items-end">
        <a href="{{ route('admin.translations.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ {{ trans_db('admin.add_new') }}</a>

        <form method="GET" class="flex gap-2 items-end">
            <div>
                <label class="text-xs text-gray-500">{{ trans_db('admin.languages') }}</label>
                <select name="language" onchange="this.form.submit()" class="border rounded px-2 py-1.5 text-sm">
                    @foreach($languages as $lang)
                        <option value="{{ $lang->code }}" {{ $selectedLanguage === $lang->code ? 'selected' : '' }}>{{ $lang->name }} ({{ $lang->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-500">{{ trans_db('admin.group') }}</label>
                <select name="group" onchange="this.form.submit()" class="border rounded px-2 py-1.5 text-sm">
                    <option value="">All</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ $selectedGroup === $group ? 'selected' : '' }}>{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs text-gray-500">{{ trans_db('admin.search') }}</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ trans_db('admin.search') }}" class="border rounded px-2 py-1.5 text-sm">
            </div>
            <button class="border rounded px-3 py-1.5 text-sm">↗</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.group') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.key') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.value') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($translations as $t)
                    <tr>
                        <td class="px-4 py-2"><span class="bg-gray-100 px-2 py-0.5 rounded text-xs font-mono">{{ $t->group }}</span></td>
                        <td class="px-4 py-2 font-mono text-xs">{{ $t->key }}</td>
                        <td class="px-4 py-2 max-w-md truncate">{{ $t->value }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('admin.translations.edit', $t) }}" class="text-indigo-600">{{ trans_db('admin.edit') }}</a>
                            <form action="{{ route('admin.translations.destroy', $t) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $translations->links() }}</div>
@endsection
