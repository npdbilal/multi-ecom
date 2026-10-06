@extends('layouts.app')

@section('title', trans_db('shop.wishlist'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-8">{{ trans_db('shop.wishlist') }}</h1>

    @if($items->count())
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8">
            @foreach($items as $item)
                @include('products.card', ['product' => $item->product])
            @endforeach
        </div>
        <div class="mt-10">{{ $items->links() }}</div>
    @else
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🤍</div>
            <h2 class="text-xl font-semibold mb-2">{{ trans_db('shop.wishlist_empty') }}</h2>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-neutral-900 text-white px-8 py-3 rounded-full font-medium">
                {{ trans_db('shop.shop_now') }}
            </a>
        </div>
    @endif
</div>
@endsection
