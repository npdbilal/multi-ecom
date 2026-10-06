@extends('layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">{{ $vendor->name }}</h1>
            <p class="text-neutral-500 text-sm">Vendor Dashboard</p>
        </div>
        <nav class="flex gap-2 text-sm">
            <a href="{{ route('vendor.dashboard') }}" class="px-4 py-2 rounded-full bg-neutral-900 text-white">Dashboard</a>
            <a href="{{ route('vendor.products') }}" class="px-4 py-2 rounded-full border hover:border-neutral-900">Products</a>
            <a href="{{ route('vendor.orders') }}" class="px-4 py-2 rounded-full border hover:border-neutral-900">Orders</a>
        </nav>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="border border-neutral-200 rounded-2xl p-5">
            <div class="text-sm text-neutral-500">Products</div>
            <div class="text-3xl font-bold">{{ $stats['products'] }}</div>
        </div>
        <div class="border border-neutral-200 rounded-2xl p-5">
            <div class="text-sm text-neutral-500">Orders</div>
            <div class="text-3xl font-bold">{{ $stats['orders'] }}</div>
        </div>
        <div class="border border-neutral-200 rounded-2xl p-5">
            <div class="text-sm text-neutral-500">Earnings</div>
            <div class="text-3xl font-bold">${{ number_format($stats['earnings'], 2) }}</div>
        </div>
        <div class="border border-neutral-200 rounded-2xl p-5">
            <div class="text-sm text-neutral-500">Pending Orders</div>
            <div class="text-3xl font-bold">{{ $stats['pending_orders'] }}</div>
        </div>
    </div>

    <h2 class="font-bold text-lg mb-4">Recent Sales</h2>
    <div class="border border-neutral-200 rounded-2xl divide-y divide-neutral-200">
        @forelse($recentItems as $item)
            <div class="p-4 flex justify-between text-sm">
                <span>{{ $item->product_name }} × {{ $item->quantity }} <span class="text-neutral-400">({{ $item->order->order_number }})</span></span>
                <span class="font-semibold">${{ number_format($item->total, 2) }}</span>
            </div>
        @empty
            <div class="p-8 text-center text-neutral-400 text-sm">No sales yet.</div>
        @endforelse
    </div>
</div>
@endsection
