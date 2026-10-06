@extends('layouts.admin')

@section('title', 'Commission Settings')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-xl">
        <form action="{{ route('admin.vendors.settings.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-600">Platform commission rate (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', $rate) }}" required
                       class="w-full border rounded px-3 py-2 mt-1">
                <p class="text-xs text-gray-400 mt-1">Applied to each vendor order item unless the vendor has a custom rate.</p>
            </div>
            <button class="bg-indigo-600 text-white px-6 py-2 rounded">Save</button>
        </form>
    </div>
@endsection
