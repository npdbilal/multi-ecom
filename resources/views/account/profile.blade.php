@extends('layouts.app')

@section('title', trans_db('shop.edit_profile'))

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ trans_db('shop.edit_profile') }}</h1>
    <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-4 bg-white border rounded-lg p-6">
        @csrf @method('PUT')
        <div>
            <label class="text-sm text-gray-600">{{ trans_db('shop.full_name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border rounded px-3 py-2 mt-1">
        </div>
        <div>
            <label class="text-sm text-gray-600">{{ trans_db('shop.email') }}</label>
            <input type="email" value="{{ $user->email }}" disabled class="w-full border rounded px-3 py-2 mt-1 bg-gray-50 text-gray-400">
        </div>
        <div>
            <label class="text-sm text-gray-600">{{ trans_db('shop.phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded px-3 py-2 mt-1">
        </div>
        <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
    </form>
</div>
@endsection
