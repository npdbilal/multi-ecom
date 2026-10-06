@extends('layouts.app')

@section('title', trans_db('shop.orders'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ trans_db('shop.orders') }}</h1>

    <div class="bg-white rounded-lg shadow divide-y">
        @forelse($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="block p-4 hover:bg-gray-50">
                <div class="flex justify-between">
                    <div>
                        <div class="font-mono font-bold">{{ $order->order_number }}</div>
                        <div class="text-sm text-gray-500">{{ $order->created_at->format('d M Y') }} · {{ $order->items_count ?? $order->items->count() }} items</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold">${{ number_format($order->total, 2) }}</div>
                        <div class="text-xs px-2 py-0.5 rounded bg-gray-100 inline-block">{{ ucfirst($order->status) }}</div>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-8 text-center text-gray-500">—</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
