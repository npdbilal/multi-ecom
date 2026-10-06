@extends('layouts.app')

@section('title', $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold">{{ $order->order_number }}</h1>
    <p class="text-gray-500 text-sm mb-6">{{ $order->created_at->format('d M Y H:i') }} · {{ ucfirst($order->status) }}</p>

    <div class="bg-white rounded-lg shadow divide-y">
        @foreach($order->items as $item)
            <div class="p-4 flex justify-between">
                <div>
                    <div class="font-semibold">{{ $item->product_name }}</div>
                    <div class="text-sm text-gray-500">{{ trans_db('shop.quantity') }}: {{ $item->quantity }}</div>
                </div>
                <div class="font-bold">${{ number_format($item->total, 2) }}</div>
            </div>
        @endforeach
        <div class="p-4 space-y-1 text-sm">
            <div class="flex justify-between"><span>{{ trans_db('shop.subtotal') }}</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between"><span>{{ trans_db('shop.shipping') }}</span><span>${{ number_format($order->shipping_cost, 2) }}</span></div>
            <div class="flex justify-between font-bold text-base"><span>{{ trans_db('shop.total') }}</span><span>${{ number_format($order->total, 2) }}</span></div>
        </div>
    </div>
</div>
@endsection
