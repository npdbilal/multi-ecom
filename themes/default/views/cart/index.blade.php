@extends('layouts.app')

@section('title', trans_db('shop.cart'))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-8">{{ trans_db('shop.cart') }}</h1>

    @if($items->count())
        <div class="grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 divide-y divide-neutral-200">
                @foreach($items as $item)
                    <div class="py-6 flex gap-5">
                        <a href="{{ route('products.show', $item->product) }}" class="w-24 h-28 rounded-xl bg-neutral-100 overflow-hidden flex-shrink-0">
                            @if($item->product->image)
                                <img src="{{ $item->product->image }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl text-neutral-300">📦</div>
                            @endif
                        </a>
                        <div class="flex-1">
                            <a href="{{ route('products.show', $item->product) }}" class="font-medium hover:underline">{{ $item->product->name }}</a>
                            <div class="text-sm text-neutral-500 mt-1">${{ number_format($item->product->price, 2) }}</div>
                            <div class="flex items-center gap-4 mt-3">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-neutral-300 rounded-full">
                                    @csrf @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                           onchange="this.form.submit()" class="w-14 text-center py-1.5 bg-transparent outline-none text-sm">
                                </form>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-sm text-neutral-400 hover:text-red-600 underline underline-offset-2">Remove</button>
                                </form>
                            </div>
                        </div>
                        <div class="font-semibold">${{ number_format($item->lineTotal(), 2) }}</div>
                    </div>
                @endforeach
            </div>

            <div>
                <div class="border border-neutral-200 rounded-2xl p-6 sticky top-24">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-neutral-500">{{ trans_db('shop.subtotal') }}</span>
                        <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <p class="text-xs text-neutral-400 mb-4">{{ trans_db('shop.shipping') }} + taxes calculated at checkout</p>
                    <a href="{{ route('checkout.index') }}"
                       class="block text-center bg-neutral-900 text-white rounded-full py-3.5 font-medium hover:bg-neutral-700 transition">
                        {{ trans_db('shop.checkout') }}
                    </a>
                    <a href="{{ route('products.index') }}" class="block text-center text-sm mt-3 underline underline-offset-4 hover:opacity-60">
                        {{ trans_db('shop.view_all') }}
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="text-xl font-semibold mb-2">{{ trans_db('shop.cart_empty') }}</h2>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-neutral-900 text-white px-8 py-3 rounded-full font-medium">
                {{ trans_db('shop.shop_now') }}
            </a>
        </div>
    @endif
</div>
@endsection
