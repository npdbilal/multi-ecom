@extends('layouts.app')

@section('title', trans_db('shop.login'))

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold mb-6">{{ trans_db('auth.login_title') }}</h1>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-600">{{ trans_db('shop.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2 mt-1">
            </div>
            <div>
                <label class="text-sm text-gray-600">{{ trans_db('auth.password') }}</label>
                <input type="password" name="password" required class="w-full border rounded px-3 py-2 mt-1">
            </div>
            <label class="flex items-center text-sm gap-2">
                <input type="checkbox" name="remember"> {{ trans_db('auth.remember_me') }}
            </label>
            <button class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold">{{ trans_db('shop.login') }}</button>
        </form>

        <p class="text-sm text-center mt-4 text-gray-500">
            {{ trans_db('auth.no_account') }}
            <a href="{{ route('register') }}" class="text-indigo-600">{{ trans_db('shop.register') }}</a>
        </p>
    </div>
</div>
@endsection
