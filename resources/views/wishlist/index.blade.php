@extends('layouts.app')

@section('title', trans_db('shop.wishlist'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ trans_db('shop.wishlist') }}</h1>
    @if($items->count())
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($items as $item)
                @include('products.card', ['product' => $item->product])
            @endforeach
        </div>
        <div class="mt-6">{{ $items->links() }}</div>
    @else
        <p class="text-gray-500">{{ trans_db('shop.wishlist_empty') }}</p>
    @endif
</div>
@endsection
