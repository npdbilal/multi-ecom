@extends('layouts.admin')

@section('title', trans_db('admin.dashboard'))

@section('content')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm text-gray-500">{{ trans_db('admin.products') }}</div>
            <div class="text-3xl font-bold">{{ $stats['products'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm text-gray-500">{{ trans_db('admin.orders') }}</div>
            <div class="text-3xl font-bold">{{ $stats['orders'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm text-gray-500">{{ trans_db('admin.customers') }}</div>
            <div class="text-3xl font-bold">{{ $stats['customers'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-sm text-gray-500">{{ trans_db('admin.revenue') }}</div>
            <div class="text-3xl font-bold">${{ number_format($stats['revenue'], 2) }}</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="font-bold mb-3">{{ trans_db('admin.recent_orders') }}</h2>
            <div class="divide-y text-sm">
                @forelse($recentOrders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="flex justify-between py-2 hover:text-indigo-600">
                        <span class="font-mono">{{ $order->order_number }}</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </a>
                @empty
                    <div class="text-gray-400 py-4">—</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <h2 class="font-bold mb-3">{{ trans_db('admin.low_stock') }}</h2>
            <div class="divide-y text-sm">
                @forelse($lowStock as $product)
                    <div class="flex justify-between py-2">
                        <span>{{ $product->name }}</span>
                        <span class="text-red-600 font-bold">{{ $product->stock }}</span>
                    </div>
                @empty
                    <div class="text-gray-400 py-4">—</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
