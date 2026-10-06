@extends('layouts.app')

@section('title', trans_db('shop.address_book'))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-8">{{ trans_db('shop.address_book') }}</h1>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            @if($addresses->count())
                <div class="space-y-4">
                    @foreach($addresses as $address)
                        <div class="border {{ $address->is_default ? 'border-neutral-900 ring-1 ring-neutral-900' : 'border-neutral-200' }} rounded-2xl p-5">
                            <div class="flex items-start justify-between mb-2">
                                <span class="font-semibold">{{ $address->label }}</span>
                                @if($address->is_default)
                                    <span class="bg-neutral-900 text-white text-xs rounded-full px-3 py-1">{{ trans_db('shop.default_address') }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-neutral-600">{{ $address->formatted() }}</p>
                            @if($address->phone)<p class="text-sm text-neutral-500 mt-1">{{ $address->phone }}</p>@endif
                            <div class="flex gap-3 mt-3 text-sm">
                                @if(! $address->is_default)
                                    <form action="{{ route('account.addresses.default', $address) }}" method="POST">@csrf
                                        <button class="underline underline-offset-4 hover:opacity-60">{{ trans_db('shop.set_default') }}</button>
                                    </form>
                                @endif
                                <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                    <button class="text-red-600 underline underline-offset-4">{{ trans_db('admin.delete') }}</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-neutral-400 text-sm">{{ trans_db('shop.no_addresses') }}</p>
            @endif
        </div>

        <div class="border border-neutral-200 rounded-2xl p-6 h-fit">
            <h2 class="font-semibold mb-4">{{ trans_db('shop.add_address') }}</h2>
            <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="label" placeholder="{{ trans_db('shop.label') }} (Home, Office)" class="border border-neutral-300 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-neutral-900">
                    <input type="text" name="full_name" required placeholder="{{ trans_db('shop.full_name') }} *" class="border border-neutral-300 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-neutral-900">
                </div>
                <input type="text" name="phone" placeholder="{{ trans_db('shop.phone') }}" class="w-full border border-neutral-300 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-neutral-900">
                <input type="text" name="address" required placeholder="{{ trans_db('shop.address') }} *" class="w-full border border-neutral-300 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-neutral-900">
                <div class="grid grid-cols-3 gap-3">
                    <input type="text" name="city" required placeholder="{{ trans_db('shop.city') }} *" class="border border-neutral-300 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-neutral-900">
                    <input type="text" name="postal_code" placeholder="{{ trans_db('shop.postal_code') }}" class="border border-neutral-300 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-neutral-900">
                    <input type="text" name="country" required placeholder="{{ trans_db('shop.country') }} *" class="border border-neutral-300 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-neutral-900">
                </div>
                <button class="bg-neutral-900 text-white rounded-full px-8 py-3 text-sm font-medium hover:bg-neutral-700">{{ trans_db('shop.add_address') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
