@extends('layouts.app')

@section('title', trans_db('shop.register'))

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold tracking-tight text-center mb-8">{{ trans_db('auth.register_title') }}</h1>

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="{{ trans_db('auth.name') }}"
               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="{{ trans_db('shop.email') }}"
               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        <input type="password" name="password" required placeholder="{{ trans_db('auth.password') }}"
               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        <input type="password" name="password_confirmation" required placeholder="{{ trans_db('auth.confirm_password') }}"
               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
        <button class="w-full bg-neutral-900 text-white py-3.5 rounded-full font-medium hover:bg-neutral-700 transition">{{ trans_db('shop.register') }}</button>
    </form>

    <p class="text-sm text-center mt-6 text-neutral-500">
        {{ trans_db('auth.have_account') }}
        <a href="{{ route('login') }}" class="underline underline-offset-4 text-neutral-900">{{ trans_db('shop.login') }}</a>
    </p>
</div>
@endsection
