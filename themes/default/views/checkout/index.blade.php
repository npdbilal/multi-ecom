@extends('layouts.app')

@section('title', trans_db('shop.checkout'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-8">{{ trans_db('shop.checkout') }}</h1>

    <form action="{{ route('checkout.store') }}" method="POST" class="grid lg:grid-cols-5 gap-10">
        @csrf
        <div class="lg:col-span-3 space-y-8">
            <section>
                <h2 class="font-semibold text-lg mb-4">{{ trans_db('shop.customer_details') }}</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required
                               placeholder="{{ trans_db('shop.full_name') }}" class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required
                               placeholder="{{ trans_db('shop.email') }}" class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="{{ trans_db('shop.phone') }}"
                               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="text" name="address" value="{{ old('address') }}" required placeholder="{{ trans_db('shop.address') }}"
                               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="text" name="city" value="{{ old('city') }}" required placeholder="{{ trans_db('shop.city') }}"
                               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="text" name="country" value="{{ old('country') }}" required placeholder="{{ trans_db('shop.country') }}"
                               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div class="md:col-span-2">
                        <textarea name="notes" rows="2" placeholder="{{ trans_db('shop.notes') }}"
                                  class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="font-semibold text-lg mb-4">{{ trans_db('shop.shipping_method') }}</h2>
                <div class="space-y-2">
                    @foreach($shippingMethods as $key => $method)
                        <label class="flex items-center justify-between border border-neutral-300 rounded-xl px-4 py-3 cursor-pointer has-[:checked]:border-neutral-900 has-[:checked]:ring-1 has-[:checked]:ring-neutral-900">
                            <span class="flex items-center gap-3 text-sm">
                                <input type="radio" name="shipping_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="accent-neutral-900">
                                {{ $method['label'] }}
                            </span>
                            <span class="text-sm font-medium">{{ $method['cost'] > 0 ? '$'.number_format($method['cost'], 2) : 'Free' }}</span>
                        </label>
                    @endforeach
                </div>
            </section>

            <section>
                <h2 class="font-semibold text-lg mb-4">{{ trans_db('shop.payment_method') }}</h2>
                <div class="space-y-2">
                    @foreach($paymentMethods as $key => $method)
                        <label class="flex items-center gap-3 border border-neutral-300 rounded-xl px-4 py-3 cursor-pointer has-[:checked]:border-neutral-900 has-[:checked]:ring-1 has-[:checked]:ring-neutral-900">
                            <input type="radio" name="payment_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="accent-neutral-900">
                            <span class="text-sm font-medium">{{ $method['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-neutral-50 rounded-2xl p-6 sticky top-24">
                <div class="space-y-4 max-h-64 overflow-auto mb-4">
                    @foreach($items as $item)
                        <div class="flex items-center gap-3">
                            <div class="relative w-14 h-14 rounded-lg bg-white border border-neutral-200 overflow-hidden flex-shrink-0">
                                @if($item->product->image)
                                    <img src="{{ $item->product->image }}" class="w-full h-full object-cover">
                                @endif
                                <span class="absolute -top-1 -right-1 bg-neutral-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $item->quantity }}</span>
                            </div>
                            <div class="flex-1 text-sm">
                                <div class="font-medium line-clamp-1">{{ $item->product->name }}</div>
                            </div>
                            <div class="text-sm font-medium">${{ number_format($item->lineTotal(), 2) }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-neutral-200 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-neutral-500">{{ trans_db('shop.subtotal') }}</span><span>${{ number_format($subtotal, 2) }}</span></div>
                    <div class="flex justify-between font-bold text-base pt-2"><span>{{ trans_db('shop.total') }}</span><span>${{ number_format($subtotal, 2) }}</span></div>
                </div>
                <button class="w-full bg-neutral-900 text-white rounded-full py-4 font-medium mt-6 hover:bg-neutral-700 transition">
                    {{ trans_db('shop.place_order') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
