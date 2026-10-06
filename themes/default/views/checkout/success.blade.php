@extends('layouts.app')

@section('title', trans_db('shop.order_placed'))

@section('content')
<div class="max-w-xl mx-auto px-4 py-20 text-center">
    <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center text-4xl mx-auto mb-6">✓</div>
    <h1 class="text-3xl font-bold tracking-tight mb-3">{{ trans_db('shop.order_placed') }}</h1>
    <p class="text-neutral-500">{{ trans_db('shop.order_number') }}: <span class="font-mono font-bold text-neutral-900">{{ $order->order_number }}</span></p>
    <p class="text-neutral-500 mt-1">{{ trans_db('shop.total') }}: <span class="font-bold text-neutral-900">${{ number_format($order->total, 2) }}</span></p>
    <div class="mt-10 flex gap-3 justify-center">
        <a href="{{ route('orders.show', $order) }}" class="bg-neutral-900 text-white px-8 py-3 rounded-full font-medium">{{ trans_db('shop.orders') }}</a>
        <a href="{{ route('home') }}" class="border border-neutral-300 px-8 py-3 rounded-full font-medium hover:border-neutral-900">{{ trans_db('shop.home') }}</a>
    </div>
</div>
@endsection
