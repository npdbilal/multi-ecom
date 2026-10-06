@extends('layouts.admin')

@section('title', trans_db('admin.orders'))

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">#</th>
                    <th class="text-left px-4 py-2">{{ trans_db('auth.name') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('shop.total') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-2 font-mono">{{ $order->order_number }}</td>
                        <td class="px-4 py-2">{{ $order->customer_name }}</td>
                        <td class="px-4 py-2"><span class="bg-gray-100 px-2 py-0.5 rounded text-xs">{{ ucfirst($order->status) }}</span></td>
                        <td class="px-4 py-2 font-bold">${{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-2"><a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600">{{ trans_db('admin.edit') }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
