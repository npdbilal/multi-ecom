@extends('layouts.app')

@section('title', trans_db('shop.cart'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ trans_db('shop.cart') }}</h1>

    @if($items->count())
        <div class="bg-white rounded-lg shadow divide-y">
            @foreach($items as $item)
                <div class="p-4 flex items-center gap-4">
                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-2xl flex-shrink-0">📦</div>
                    <div class="flex-1">
                        <a href="{{ route('products.show', $item->product) }}" class="font-semibold">{{ $item->product->name }}</a>
                        <div class="text-sm text-gray-500">${{ number_format($item->product->price, 2) }} × {{ $item->quantity }}</div>
                    </div>
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex gap-2">
                        @csrf @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="border rounded w-16 px-2 py-1 text-sm">
                        <button class="text-indigo-600 text-sm">✓</button>
                    </form>
                    <div class="font-bold w-24 text-right">${{ number_format($item->lineTotal(), 2) }}</div>
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-red-500 text-sm">✕</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-between items-center">
            <div class="text-lg">{{ trans_db('shop.subtotal') }}: <span class="font-bold">${{ number_format($subtotal, 2) }}</span></div>
            <a href="{{ route('checkout.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold">{{ trans_db('shop.checkout') }} →</a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="text-5xl mb-4">🛒</div>
            <p class="text-gray-500">{{ trans_db('shop.cart_empty') }}</p>
            <a href="{{ route('products.index') }}" class="text-indigo-600 mt-4 inline-block">{{ trans_db('shop.shop_now') }}</a>
        </div>
    @endif
</div>
@endsection
