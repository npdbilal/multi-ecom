@extends('layouts.app')

@section('title', trans_db('shop.order_history'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ trans_db('shop.order_history') }}</h1>
    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $order->order_number }}<div class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y') }}</div></td>
                        <td class="px-4 py-3">{{ ucfirst($order->status) }}</td>
                        <td class="px-4 py-3 font-semibold">${{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('orders.show', $order) }}" class="text-indigo-600">→</a></td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-6 text-gray-400">—</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
