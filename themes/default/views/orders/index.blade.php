@extends('layouts.app')

@section('title', trans_db('shop.orders'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-8">{{ trans_db('shop.orders') }}</h1>

    <div class="border border-neutral-200 rounded-2xl divide-y divide-neutral-200">
        @forelse($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-5 hover:bg-neutral-50">
                <div>
                    <div class="font-mono font-semibold">{{ $order->order_number }}</div>
                    <div class="text-sm text-neutral-500">{{ $order->created_at->format('d M Y') }}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold">${{ number_format($order->total, 2) }}</div>
                    <span class="text-xs bg-neutral-100 px-2.5 py-1 rounded-full">{{ ucfirst($order->status) }}</span>
                </div>
            </a>
        @empty
            <div class="p-12 text-center text-neutral-400">—</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
</div>
@endsection
