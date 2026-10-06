@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Breadcrumbs --}}
    <nav class="text-xs text-neutral-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-neutral-900">{{ trans_db('shop.home') }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-neutral-900">{{ trans_db('shop.products') }}</a>
        <span class="mx-1">/</span>
        <span class="text-neutral-900">{{ $product->name }}</span>
    </nav>

    <div class="grid md:grid-cols-2 gap-10 lg:gap-16">
        {{-- Gallery --}}
        <div>
            <div class="rounded-2xl overflow-hidden bg-neutral-100 aspect-square">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-8xl text-neutral-300">📦</div>
                @endif
            </div>
        </div>

        {{-- Details --}}
        <div>
            <div class="text-xs uppercase tracking-[0.2em] text-neutral-400 mb-2">{{ $product->category?->name }}</div>
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight">{{ $product->name }}</h1>

            <div class="flex items-center gap-3 mt-4">
                <span class="text-2xl font-bold">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_price)
                    <span class="text-neutral-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                    <span class="bg-red-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">-{{ $product->discountPercent() }}%</span>
                @endif
            </div>

            @if($product->short_description)
                <p class="text-neutral-500 mt-4">{{ $product->short_description }}</p>
            @endif

            <div class="mt-3 text-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $product->inStock() ? 'bg-green-500' : 'bg-red-500' }}"></span>
                <span class="{{ $product->inStock() ? 'text-green-700' : 'text-red-600' }}">
                    {{ $product->inStock() ? trans_db('shop.in_stock') : trans_db('shop.out_of_stock') }}
                </span>
            </div>

            @if($product->inStock())
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-6">
                    @csrf
                    <div class="flex gap-3">
                        <div class="flex items-center border border-neutral-300 rounded-full">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-16 text-center py-3 bg-transparent outline-none">
                        </div>
                        <button class="flex-1 bg-neutral-900 text-white rounded-full font-medium hover:bg-neutral-700 transition py-3">
                            {{ trans_db('shop.add_to_cart') }}
                        </button>
                    </div>
                </form>
            @endif

            {{-- Accordions --}}
            <div class="mt-8 border-t border-neutral-200">
                <details class="border-b border-neutral-200 py-4 group" open>
                    <summary class="font-medium cursor-pointer list-none flex justify-between">
                        {{ trans_db('shop.description') }} <span class="text-neutral-400">+</span>
                    </summary>
                    <p class="text-sm text-neutral-500 mt-3 whitespace-pre-line">{{ $product->description }}</p>
                </details>
                <details class="border-b border-neutral-200 py-4">
                    <summary class="font-medium cursor-pointer list-none flex justify-between">
                        {{ trans_db('shop.shipping') }} <span class="text-neutral-400">+</span>
                    </summary>
                    <p class="text-sm text-neutral-500 mt-3">{{ trans_db('shop.standard_shipping') }} · {{ trans_db('shop.cash_on_delivery') }}</p>
                </details>
            </div>
        </div>
    </div>

    @if($related->count())
        <div class="mt-16">
            <h2 class="text-2xl font-bold tracking-tight mb-6">{{ trans_db('shop.related_products') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8">
                @foreach($related as $product)
                    @include('products.card', ['product' => $product])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
