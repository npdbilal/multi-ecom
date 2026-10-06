@extends('layouts.app')

@section('title', trans_db('shop.products'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-6">
        {{-- Filters --}}
        <aside class="w-full md:w-56 flex-shrink-0">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-bold mb-3">{{ trans_db('shop.categories') }}</h3>
                <ul class="space-y-1 text-sm">
                    <li><a href="{{ route('products.index') }}" class="hover:text-indigo-600">{{ trans_db('shop.view_all') }}</a></li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                               class="hover:text-indigo-600 {{ request('category') === $category->slug ? 'font-bold text-indigo-600' : '' }}">
                                {{ $category->name }} ({{ $category->products()->active()->count() }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <div class="flex-1">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">{{ trans_db('shop.products') }}</h1>
                <form method="GET" class="text-sm">
                    @foreach(request()->except('sort') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>{{ trans_db('shop.newest') }}</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>{{ trans_db('shop.price_low_high') }}</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ trans_db('shop.price_high_low') }}</option>
                    </select>
                </form>
            </div>

            @if($products->count())
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($products as $product)
                        @include('products.card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-6">{{ $products->links() }}</div>
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center text-gray-500">{{ trans_db('shop.cart_empty') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
