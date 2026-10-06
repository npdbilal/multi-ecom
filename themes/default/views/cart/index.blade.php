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
                            @php $thumb = $item->product->displayImage(); @endphp
                            @if($thumb)
                                <img src="{{ $thumb }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl text-neutral-300">📦</div>
                            @endif
                        </a>
                        <div class="flex-1">
                            <a href="{{ route('products.show', $item->product) }}" class="font-medium hover:underline">{{ $item->product->name }}</a>
                            @if($item->variant)
                                <div class="text-xs text-neutral-500 mt-0.5">{{ $item->variant->name }}</div>
                            @endif
                            <div class="text-sm text-neutral-500 mt-1">${{ number_format($item->unitPrice(), 2) }}</div>
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
                    {{-- Coupon --}}
                    @if($coupon['coupon'])
                        <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-4 text-sm">
                            <span class="font-mono font-semibold text-green-800">{{ $coupon['coupon']->code }}</span>
                            <span class="text-green-700">−${{ number_format($coupon['discount'], 2) }}</span>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">@csrf @method('DELETE')
                                <button class="text-green-700 hover:text-red-600 ml-2">✕</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2 mb-4">
                            @csrf
                            <input type="text" name="code" placeholder="{{ trans_db('shop.enter_coupon') }}" required
                                   class="flex-1 border border-neutral-300 rounded-xl px-4 py-2.5 text-sm uppercase outline-none focus:border-neutral-900">
                            <button class="bg-neutral-900 text-white rounded-xl px-5 text-sm font-medium hover:bg-neutral-700">{{ trans_db('shop.apply_coupon') }}</button>
                        </form>
                    @endif

                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-neutral-500">{{ trans_db('shop.subtotal') }}</span>
                        <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($coupon['discount'] > 0)
                        <div class="flex justify-between text-sm mb-2 text-green-700">
                            <span>{{ trans_db('shop.discount') }}</span>
                            <span>−${{ number_format($coupon['discount'], 2) }}</span>
                        </div>
                    @endif
                    <p class="text-xs text-neutral-400 mb-4">{{ trans_db('shop.shipping') }} + {{ trans_db('shop.tax') }} calculated at checkout</p>
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
