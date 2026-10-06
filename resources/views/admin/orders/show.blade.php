@extends('layouts.admin')

@section('title', $order->order_number)

@section('content')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="font-bold mb-4">Items</h2>
            <div class="divide-y text-sm">
                @foreach($order->items as $item)
                    <div class="flex justify-between py-2">
                        <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                        <span class="font-bold">${{ number_format($item->total, 2) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t mt-4 pt-4 text-sm space-y-1">
                <div class="flex justify-between"><span>{{ trans_db('shop.subtotal') }}</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span>{{ trans_db('shop.shipping') }}</span><span>${{ number_format($order->shipping_cost, 2) }}</span></div>
                <div class="flex justify-between font-bold"><span>{{ trans_db('shop.total') }}</span><span>${{ number_format($order->total, 2) }}</span></div>
            </div>

            <h2 class="font-bold mt-6 mb-2">{{ trans_db('shop.customer_details') }}</h2>
            <div class="text-sm text-gray-600">
                {{ $order->customer_name }} · {{ $order->customer_email }} · {{ $order->customer_phone }}<br>
                {{ $order->shipping_address['address'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['country'] ?? '' }}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="font-bold mb-4">{{ trans_db('admin.status') }}</h2>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-3">
                @csrf @method('PUT')
                <select name="status" class="w-full border rounded px-3 py-2">
                    @foreach(\App\Models\Order::statuses() as $status)
                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <select name="payment_status" class="w-full border rounded px-3 py-2">
                    @foreach(['pending', 'paid', 'failed'] as $ps)
                        <option value="{{ $ps }}" {{ $order->payment_status === $ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
                    @endforeach
                </select>
                <button class="w-full bg-indigo-600 text-white py-2 rounded">{{ trans_db('admin.save') }}</button>
            </form>
        </div>
    </div>
@endsection
