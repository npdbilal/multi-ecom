@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    {{-- Hero --}}
    <section class="bg-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl font-bold mb-3">{{ trans_db('shop.hero_title') }}</h1>
            <p class="text-indigo-100 mb-6">{{ trans_db('shop.hero_subtitle') }}</p>
            <a href="{{ route('products.index') }}" class="bg-white text-indigo-700 px-6 py-3 rounded-lg font-semibold">{{ trans_db('shop.shop_now') }}</a>
        </div>
    </section>

    {{-- Categories --}}
    @if($categories->count())
        <section class="max-w-7xl mx-auto px-4 py-10">
            <h2 class="text-2xl font-bold mb-4">{{ trans_db('shop.shop_by_category') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md">
                        <div class="text-3xl mb-2">🗂️</div>
                        <div class="font-semibold text-sm">{{ $category->name }}</div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured --}}
    <section class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">{{ trans_db('shop.featured_products') }}</h2>
            <a href="{{ route('products.index') }}" class="text-indigo-600 text-sm">{{ trans_db('shop.view_all') }} →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($featured as $product)
                @include('products.card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- Latest --}}
    <section class="max-w-7xl mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-4">{{ trans_db('shop.latest_products') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($latest as $product)
                @include('products.card', ['product' => $product])
            @endforeach
        </div>
    </section>
@endsection
