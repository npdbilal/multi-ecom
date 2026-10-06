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

                @if($addresses->count())
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">{{ trans_db('shop.address_book') }}</label>
                        <select id="address-picker" class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900 mb-3">
                            <option value="">— {{ trans_db('shop.address') }} —</option>
                            @foreach($addresses as $addr)
                                <option value="{{ $addr->id }}" data-name="{{ $addr->full_name }}" data-phone="{{ $addr->phone }}"
                                        data-address="{{ $addr->address }}" data-city="{{ $addr->city }}" data-country="{{ $addr->country }}">
                                    {{ $addr->label }}: {{ $addr->formatted() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="customer_name" id="f_customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required
                               placeholder="{{ trans_db('shop.full_name') }}" class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required
                               placeholder="{{ trans_db('shop.email') }}" class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="text" name="customer_phone" id="f_customer_phone" value="{{ old('customer_phone') }}" placeholder="{{ trans_db('shop.phone') }}"
                               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="text" name="address" id="f_address" value="{{ old('address') }}" required placeholder="{{ trans_db('shop.address') }}"
                               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="text" name="city" id="f_city" value="{{ old('city') }}" required placeholder="{{ trans_db('shop.city') }}"
                               class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900">
                    </div>
                    <div>
                        <input type="text" name="country" id="f_country" value="{{ old('country') }}" required placeholder="{{ trans_db('shop.country') }}"
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
                                @php $cthumb = $item->product->displayImage(); @endphp
                                @if($cthumb)
                                    <img src="{{ $cthumb }}" class="w-full h-full object-cover">
                                @endif
                                <span class="absolute -top-1 -right-1 bg-neutral-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $item->quantity }}</span>
                            </div>
                            <div class="flex-1 text-sm">
                                <div class="font-medium line-clamp-1">{{ $item->product->name }}</div>
                                @if($item->variant)<div class="text-xs text-neutral-500">{{ $item->variant->name }}</div>@endif
                            </div>
                            <div class="text-sm font-medium">${{ number_format($item->lineTotal(), 2) }}</div>
                        </div>
                    @endforeach
                </div>

                @if($coupon)
                    <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-xl px-3 py-2 mb-3 text-sm">
                        <span class="font-mono font-semibold text-green-800">{{ $coupon->code }}</span>
                        <span class="text-green-700">−${{ number_format($discount, 2) }}</span>
                    </div>
                @endif

                <div class="border-t border-neutral-200 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-neutral-500">{{ trans_db('shop.subtotal') }}</span><span>${{ number_format($subtotal, 2) }}</span></div>
                    @if($discount > 0)
                        <div class="flex justify-between text-green-700"><span>{{ trans_db('shop.discount') }}</span><span>−${{ number_format($discount, 2) }}</span></div>
                    @endif
                    @if($tax > 0)
                        <div class="flex justify-between"><span class="text-neutral-500">{{ trans_db('shop.tax') }} ({{ rtrim(rtrim(number_format($tax_rate, 2), '0'), '.') }}%)</span><span>${{ number_format($tax, 2) }}</span></div>
                    @elseif($tax_included && $tax_rate > 0)
                        <div class="flex justify-between text-neutral-400 text-xs"><span>{{ trans_db('shop.tax') }} {{ rtrim(rtrim(number_format($tax_rate, 2), '0'), '.') }}% included</span><span>—</span></div>
                    @endif
                    <div class="flex justify-between font-bold text-base pt-2"><span>{{ trans_db('shop.total') }}</span><span>${{ number_format($taxable + $tax, 2) }}</span></div>
                    <p class="text-xs text-neutral-400">+ {{ trans_db('shop.shipping') }}</p>
                </div>
                <button class="w-full bg-neutral-900 text-white rounded-full py-4 font-medium mt-6 hover:bg-neutral-700 transition">
                    {{ trans_db('shop.place_order') }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('address-picker')?.addEventListener('change', function () {
    const opt = this.selectedOptions[0];
    if (!opt || !opt.value) return;
    document.getElementById('f_customer_name').value = opt.dataset.name || '';
    document.getElementById('f_customer_phone').value = opt.dataset.phone || '';
    document.getElementById('f_address').value = opt.dataset.address || '';
    document.getElementById('f_city').value = opt.dataset.city || '';
    document.getElementById('f_country').value = opt.dataset.country || '';
});
</script>
@endsection
