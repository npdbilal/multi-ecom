@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold tracking-tight mb-6">My Orders</h1>

    <div class="border border-neutral-200 rounded-2xl divide-y divide-neutral-200">
        @forelse($items as $item)
            <div class="p-4 flex justify-between text-sm">
                <div>
                    <div class="font-medium">{{ $item->product_name }} × {{ $item->quantity }}</div>
                    <div class="text-neutral-500">{{ $item->order->order_number }} · {{ $item->order->created_at->format('d M Y') }} · {{ ucfirst($item->order->status) }}</div>
                </div>
                <div class="font-semibold">${{ number_format($item->total, 2) }}</div>
            </div>
        @empty
            <div class="p-8 text-center text-neutral-400 text-sm">No orders yet.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
