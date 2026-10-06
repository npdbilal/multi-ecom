@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="aspect-square bg-gray-200 flex items-center justify-center text-8xl">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    📦
                @endif
            </div>
        </div>

        <div>
            <div class="text-sm text-gray-500">{{ $product->category?->name }} · {{ $product->sku }}</div>
            <h1 class="text-3xl font-bold mt-1">{{ $product->name }}</h1>

            <div class="flex items-center gap-3 mt-3">
                <span class="text-3xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_price)
                    <span class="text-gray-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-sm">-{{ $product->discountPercent() }}%</span>
                @endif
            </div>

            <p class="text-gray-600 mt-4">{{ $product->short_description }}</p>

            <div class="mt-2 text-sm {{ $product->inStock() ? 'text-green-600' : 'text-red-600' }}">
                {{ $product->inStock() ? trans_db('shop.in_stock').' ('.$product->stock.')' : trans_db('shop.out_of_stock') }}
            </div>

            @if($product->inStock())
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-6 flex gap-3">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                           class="border rounded px-3 py-2 w-20">
                    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700">
                        {{ trans_db('shop.add_to_cart') }}
                    </button>
                </form>
            @endif

            <div class="mt-8">
                <h3 class="font-bold mb-2">{{ trans_db('shop.description') }}</h3>
                <p class="text-gray-600 text-sm whitespace-pre-line">{{ $product->description }}</p>
            </div>
        </div>
    </div>

    @if($related->count())
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">{{ trans_db('shop.related_products') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($related as $product)
                    @include('products.card', ['product' => $product])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
