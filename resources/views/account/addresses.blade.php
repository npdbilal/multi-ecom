@extends('layouts.app')

@section('title', trans_db('shop.address_book'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ trans_db('shop.address_book') }}</h1>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="space-y-4">
            @forelse($addresses as $address)
                <div class="border rounded-lg p-4 {{ $address->is_default ? 'border-indigo-500' : '' }}">
                    <div class="font-semibold">{{ $address->label }} @if($address->is_default)<span class="text-xs bg-indigo-100 text-indigo-700 rounded-full px-2 py-0.5">{{ trans_db('shop.default_address') }}</span>@endif</div>
                    <p class="text-sm text-gray-600 mt-1">{{ $address->formatted() }}</p>
                    <div class="flex gap-3 mt-2 text-sm">
                        @if(! $address->is_default)
                            <form action="{{ route('account.addresses.default', $address) }}" method="POST">@csrf<button class="text-indigo-600">{{ trans_db('shop.set_default') }}</button></form>
                        @endif
                        <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')<button class="text-red-600">{{ trans_db('admin.delete') }}</button></form>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">{{ trans_db('shop.no_addresses') }}</p>
            @endforelse
        </div>
        <div class="border rounded-lg p-5 h-fit">
            <h2 class="font-semibold mb-3">{{ trans_db('shop.add_address') }}</h2>
            <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="text" name="label" placeholder="{{ trans_db('shop.label') }}" class="w-full border rounded px-3 py-2 text-sm">
                <input type="text" name="full_name" required placeholder="{{ trans_db('shop.full_name') }} *" class="w-full border rounded px-3 py-2 text-sm">
                <input type="text" name="phone" placeholder="{{ trans_db('shop.phone') }}" class="w-full border rounded px-3 py-2 text-sm">
                <input type="text" name="address" required placeholder="{{ trans_db('shop.address') }} *" class="w-full border rounded px-3 py-2 text-sm">
                <div class="grid grid-cols-3 gap-2">
                    <input type="text" name="city" required placeholder="{{ trans_db('shop.city') }} *" class="border rounded px-3 py-2 text-sm">
                    <input type="text" name="postal_code" placeholder="{{ trans_db('shop.postal_code') }}" class="border rounded px-3 py-2 text-sm">
                    <input type="text" name="country" required placeholder="{{ trans_db('shop.country') }} *" class="border rounded px-3 py-2 text-sm">
                </div>
                <button class="bg-indigo-600 text-white px-6 py-2 rounded text-sm">{{ trans_db('shop.add_address') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
