@extends('layouts.app')

@section('title', trans_db('shop.order_history'))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-8">{{ trans_db('shop.order_history') }}</h1>

    @if($orders->count())
        <div class="border border-neutral-200 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="text-left px-4 py-3">{{ trans_db('shop.order_number') }}</th>
                        <th class="text-left px-4 py-3">{{ trans_db('admin.status') }}</th>
                        <th class="text-left px-4 py-3">{{ trans_db('shop.total') }}</th>
                        <th class="text-left px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-4 py-3 font-mono">{{ $order->order_number }}<div class="text-xs text-neutral-400 font-sans">{{ $order->created_at->format('M d, Y') }}</div></td>
                            <td class="px-4 py-3"><span class="bg-neutral-100 rounded-full px-3 py-1 text-xs">{{ ucfirst($order->status) }}</span></td>
                            <td class="px-4 py-3 font-semibold">${{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('orders.show', $order) }}" class="underline underline-offset-4">{{ trans_db('shop.view_all') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    @else
        <div class="text-center py-20 text-neutral-400">
            <div class="text-5xl mb-4">🧾</div>
            <p>No orders yet.</p>
        </div>
    @endif
</div>
@endsection
