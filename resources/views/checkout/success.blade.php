@extends('layouts.app')

@section('title', trans_db('shop.order_placed'))

@section('content')
<div class="max-w-xl mx-auto px-4 py-16 text-center">
    <div class="text-6xl mb-4">✅</div>
    <h1 class="text-2xl font-bold mb-2">{{ trans_db('shop.order_placed') }}</h1>
    <p class="text-gray-600">{{ trans_db('shop.order_number') }}: <span class="font-mono font-bold">{{ $order->order_number }}</span></p>
    <p class="text-gray-600 mt-1">{{ trans_db('shop.total') }}: <span class="font-bold">${{ number_format($order->total, 2) }}</span></p>
    <div class="mt-8 flex gap-3 justify-center">
        <a href="{{ route('orders.show', $order) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg">{{ trans_db('shop.orders') }}</a>
        <a href="{{ route('home') }}" class="border px-6 py-2 rounded-lg">{{ trans_db('shop.home') }}</a>
    </div>
</div>
@endsection
