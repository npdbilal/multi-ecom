@extends('layouts.app')

@section('title', $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight">{{ $order->order_number }}</h1>
    <p class="text-neutral-500 text-sm mt-1 mb-8">{{ $order->created_at->format('d M Y H:i') }} · {{ ucfirst($order->status) }}</p>

    <div class="border border-neutral-200 rounded-2xl divide-y divide-neutral-200">
        @foreach($order->items as $item)
            <div class="p-5 flex justify-between items-center">
                <div>
                    <div class="font-medium">{{ $item->product_name }}</div>
                    @if($item->variant_label)<div class="text-xs text-neutral-500">{{ $item->variant_label }}</div>@endif
                    <div class="text-sm text-neutral-500">{{ trans_db('shop.quantity') }}: {{ $item->quantity }}</div>
                </div>
                <div class="font-semibold">${{ number_format($item->total, 2) }}</div>
            </div>
        @endforeach
        <div class="p-5 space-y-2 text-sm bg-neutral-50 rounded-b-2xl">
            <div class="flex justify-between"><span class="text-neutral-500">{{ trans_db('shop.subtotal') }}</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            @if($order->discount > 0)
                <div class="flex justify-between text-green-700"><span>{{ trans_db('shop.discount') }}{{ $order->coupon_code ? ' ('.$order->coupon_code.')' : '' }}</span><span>−${{ number_format($order->discount, 2) }}</span></div>
            @endif
            <div class="flex justify-between"><span class="text-neutral-500">{{ trans_db('shop.shipping') }}</span><span>${{ number_format($order->shipping_cost, 2) }}</span></div>
            @if($order->tax > 0)
                <div class="flex justify-between"><span class="text-neutral-500">{{ trans_db('shop.tax') }}</span><span>${{ number_format($order->tax, 2) }}</span></div>
            @endif
            <div class="flex justify-between font-bold text-base"><span>{{ trans_db('shop.total') }}</span><span>${{ number_format($order->total, 2) }}</span></div>
        </div>
    </div>
</div>
@endsection
