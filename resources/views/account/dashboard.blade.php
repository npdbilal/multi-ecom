@extends('layouts.app')

@section('title', trans_db('shop.account_dashboard'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-1">{{ trans_db('shop.my_account') }}</h1>
    <p class="text-gray-500 text-sm mb-6">{{ $user->name }} · {{ $user->email }}</p>

    <div class="grid md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('account.orders') }}" class="border rounded-lg p-5 hover:border-indigo-500">🧾 <b>{{ trans_db('shop.order_history') }}</b><div class="text-sm text-gray-500">{{ $user->orders()->count() }}</div></a>
        <a href="{{ route('wishlist.index') }}" class="border rounded-lg p-5 hover:border-indigo-500">🤍 <b>{{ trans_db('shop.wishlist') }}</b><div class="text-sm text-gray-500">{{ $wishlistCount }}</div></a>
        <a href="{{ route('account.addresses') }}" class="border rounded-lg p-5 hover:border-indigo-500">📍 <b>{{ trans_db('shop.address_book') }}</b><div class="text-sm text-gray-500">{{ $user->addresses()->count() }}</div></a>
        <a href="{{ route('account.profile.edit') }}" class="border rounded-lg p-5 hover:border-indigo-500">👤 <b>{{ trans_db('shop.edit_profile') }}</b></a>
    </div>

    <h2 class="font-bold mb-3">{{ trans_db('shop.order_history') }}</h2>
    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $order->order_number }}</td>
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
</div>
@endsection
