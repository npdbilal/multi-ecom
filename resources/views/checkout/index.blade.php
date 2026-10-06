@extends('layouts.app')

@section('title', trans_db('shop.checkout'))

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ trans_db('shop.checkout') }}</h1>

    <form action="{{ route('checkout.store') }}" method="POST" class="grid md:grid-cols-3 gap-6">
        @csrf
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-bold mb-4">{{ trans_db('shop.customer_details') }}</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">{{ trans_db('shop.full_name') }}</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required class="w-full border rounded px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">{{ trans_db('shop.email') }}</label>
                        <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required class="w-full border rounded px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">{{ trans_db('shop.phone') }}</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="w-full border rounded px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">{{ trans_db('shop.address') }}</label>
                        <input type="text" name="address" value="{{ old('address') }}" required class="w-full border rounded px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">{{ trans_db('shop.city') }}</label>
                        <input type="text" name="city" value="{{ old('city') }}" required class="w-full border rounded px-3 py-2 mt-1">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">{{ trans_db('shop.country') }}</label>
                        <input type="text" name="country" value="{{ old('country') }}" required class="w-full border rounded px-3 py-2 mt-1">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">{{ trans_db('shop.notes') }}</label>
                        <textarea name="notes" rows="2" class="w-full border rounded px-3 py-2 mt-1">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-bold mb-4">{{ trans_db('shop.shipping_method') }}</h2>
                @foreach($shippingMethods as $key => $method)
                    <label class="flex justify-between border rounded px-4 py-2 mb-2 cursor-pointer">
                        <span><input type="radio" name="shipping_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="mr-2">{{ $method['label'] }}</span>
                        <span>{{ $method['cost'] > 0 ? '$'.number_format($method['cost'], 2) : '—' }}</span>
                    </label>
                @endforeach
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-bold mb-4">{{ trans_db('shop.payment_method') }}</h2>
                @foreach($paymentMethods as $key => $method)
                    <label class="flex border rounded px-4 py-2 mb-2 cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="mr-2">
                        {{ $method['label'] }}
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow p-6 sticky top-20">
                <h2 class="font-bold mb-4">{{ trans_db('shop.cart') }}</h2>
                @foreach($items as $item)
                    <div class="flex justify-between text-sm py-1">
                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span>${{ number_format($item->lineTotal(), 2) }}</span>
                    </div>
                @endforeach
                <div class="border-t mt-3 pt-3 flex justify-between font-bold">
                    <span>{{ trans_db('shop.subtotal') }}</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <button class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold mt-4">{{ trans_db('shop.place_order') }}</button>
            </div>
        </div>
    </form>
</div>
@endsection
