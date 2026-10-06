@extends('layouts.app')

@section('title', trans_db('shop.products'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold tracking-tight mb-2">{{ trans_db('shop.products') }}</h1>
    <p class="text-neutral-500 text-sm mb-8">{{ $products->total() }} {{ trans_db('shop.products') }}</p>

    <div class="flex flex-col lg:flex-row gap-10">
        {{-- Sidebar filters --}}
        <aside class="w-full lg:w-60 flex-shrink-0">
            <form method="GET" id="filters" class="space-y-6">
                <div>
                    <h3 class="font-semibold text-sm mb-3">{{ trans_db('shop.search') }}</h3>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ trans_db('shop.search') }}…"
                           class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-neutral-900">
                </div>

                <div>
                    <h3 class="font-semibold text-sm mb-3">{{ trans_db('shop.categories') }}</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="{{ route('products.index', request()->except('category')) }}"
                               class="{{ ! request('category') ? 'font-bold' : 'text-neutral-500 hover:text-neutral-900' }}">
                                {{ trans_db('shop.view_all') }}
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('products.index', array_merge(request()->except('category'), ['category' => $category->slug])) }}"
                                   class="{{ request('category') === $category->slug ? 'font-bold' : 'text-neutral-500 hover:text-neutral-900' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-sm mb-3">{{ trans_db('shop.price_low_high') }} / {{ trans_db('shop.price_high_low') }}</h3>
                    <div class="flex gap-2">
                        <input type="number" step="0.01" name="min_price" value="{{ request('min_price') }}" placeholder="{{ trans_db('shop.min_price') }}"
                               class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-neutral-900">
                        <input type="number" step="0.01" name="max_price" value="{{ request('max_price') }}" placeholder="{{ trans_db('shop.max_price') }}"
                               class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm outline-none focus:border-neutral-900">
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-sm mb-3">{{ trans_db('shop.sort_by') }}</h3>
                    <select name="sort" onchange="document.getElementById('filters').submit()" class="w-full border border-neutral-300 rounded-lg px-3 py-2 text-sm">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>{{ trans_db('shop.newest') }}</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>{{ trans_db('shop.price_low_high') }}</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ trans_db('shop.price_high_low') }}</option>
                    </select>
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="in_stock" value="1" {{ request()->boolean('in_stock') ? 'checked' : '' }} class="accent-neutral-900">
                    {{ trans_db('shop.in_stock_only') }}
                </label>

                <div class="flex gap-2">
                    <button class="flex-1 bg-neutral-900 text-white rounded-full py-2 text-sm font-medium hover:bg-neutral-700">{{ trans_db('shop.apply_filters') }}</button>
                    <a href="{{ route('products.index') }}" class="border border-neutral-300 rounded-full px-4 py-2 text-sm hover:border-neutral-900">{{ trans_db('shop.clear_filters') }}</a>
                </div>
            </form>
        </aside>

        {{-- Grid --}}
        <div class="flex-1">
            @if($products->count())
                <div class="grid grid-cols-2 xl:grid-cols-3 gap-x-4 gap-y-8">
                    @foreach($products as $product)
                        @include('products.card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-10">{{ $products->links() }}</div>
            @else
                <div class="text-center py-20 text-neutral-400">
                    <div class="text-5xl mb-4">🔍</div>
                    <p>{{ trans_db('shop.no_products_found') }}</p>
                    <a href="{{ route('products.index') }}" class="inline-block mt-4 text-sm underline underline-offset-4">{{ trans_db('shop.clear_filters') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
