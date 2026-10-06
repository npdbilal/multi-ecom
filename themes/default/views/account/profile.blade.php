@extends('layouts.app')

@section('title', trans_db('shop.edit_profile'))

@section('content')
<div class="max-w-xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-8">{{ trans_db('shop.edit_profile') }}</h1>

    <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-4 bg-white border border-neutral-200 rounded-2xl p-6">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-2">{{ trans_db('shop.full_name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">{{ trans_db('shop.email') }}</label>
            <input type="email" value="{{ $user->email }}" disabled class="w-full border border-neutral-200 bg-neutral-50 rounded-xl px-4 py-3 text-sm text-neutral-400">
            <p class="text-xs text-neutral-400 mt-1">Email is managed via Firebase sign-in.</p>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">{{ trans_db('shop.phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        </div>
        <button class="bg-neutral-900 text-white rounded-full px-8 py-3 text-sm font-medium hover:bg-neutral-700">{{ trans_db('admin.save') }}</button>
    </form>
</div>
@endsection
