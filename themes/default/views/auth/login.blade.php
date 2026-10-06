@extends('layouts.app')

@section('title', trans_db('shop.login'))

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold tracking-tight text-center mb-8">{{ trans_db('auth.login_title') }}</h1>

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="{{ trans_db('shop.email') }}"
               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        <input type="password" name="password" required placeholder="{{ trans_db('auth.password') }}"
               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        <label class="flex items-center text-sm gap-2 text-neutral-500">
            <input type="checkbox" name="remember" class="accent-neutral-900"> {{ trans_db('auth.remember_me') }}
        </label>
        <button class="w-full bg-neutral-900 text-white py-3.5 rounded-full font-medium hover:bg-neutral-700 transition">{{ trans_db('shop.login') }}</button>
    </form>

    <p class="text-sm text-center mt-6 text-neutral-500">
        {{ trans_db('auth.no_account') }}
        <a href="{{ route('register') }}" class="underline underline-offset-4 text-neutral-900">{{ trans_db('shop.register') }}</a>
    </p>
</div>
@endsection
