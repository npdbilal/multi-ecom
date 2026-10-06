@extends('layouts.app')

@section('title', trans_db('shop.account_dashboard'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-2">{{ trans_db('shop.my_account') }}</h1>
    <p class="text-neutral-500 text-sm mb-8">{{ $user->name }} · {{ $user->email }}</p>

    <div class="grid md:grid-cols-4 gap-4 mb-10">
        <a href="{{ route('account.orders') }}" class="border border-neutral-200 rounded-2xl p-6 hover:border-neutral-900 transition">
            <div class="text-3xl mb-2">🧾</div>
            <div class="font-semibold">{{ trans_db('shop.order_history') }}</div>
            <div class="text-sm text-neutral-500">{{ $user->orders()->count() }} {{ trans_db('shop.orders') }}</div>
        </a>
        <a href="{{ route('wishlist.index') }}" class="border border-neutral-200 rounded-2xl p-6 hover:border-neutral-900 transition">
            <div class="text-3xl mb-2">🤍</div>
            <div class="font-semibold">{{ trans_db('shop.wishlist') }}</div>
            <div class="text-sm text-neutral-500">{{ $wishlistCount }} {{ trans_db('shop.products') }}</div>
        </a>
        <a href="{{ route('account.addresses') }}" class="border border-neutral-200 rounded-2xl p-6 hover:border-neutral-900 transition">
            <div class="text-3xl mb-2">📍</div>
            <div class="font-semibold">{{ trans_db('shop.address_book') }}</div>
            <div class="text-sm text-neutral-500">{{ $user->addresses()->count() }} saved</div>
        </a>
        <a href="{{ route('account.profile.edit') }}" class="border border-neutral-200 rounded-2xl p-6 hover:border-neutral-900 transition">
            <div class="text-3xl mb-2">👤</div>
            <div class="font-semibold">{{ trans_db('shop.edit_profile') }}</div>
            <div class="text-sm text-neutral-500">{{ trans_db('shop.full_name') }}, {{ trans_db('shop.phone') }}</div>
        </a>
    </div>

    <h2 class="text-xl font-bold tracking-tight mb-4">{{ trans_db('shop.order_history') }}</h2>
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
                            <td class="px-4 py-3 font-mono">{{ $order->order_number }}</td>
                            <td class="px-4 py-3"><span class="bg-neutral-100 rounded-full px-3 py-1 text-xs">{{ ucfirst($order->status) }}</span></td>
                            <td class="px-4 py-3 font-semibold">${{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('orders.show', $order) }}" class="underline underline-offset-4">{{ trans_db('shop.view_all') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-neutral-400 text-sm">{{ trans_db('shop.cart_empty') }}</p>
    @endif
</div>
@endsection
