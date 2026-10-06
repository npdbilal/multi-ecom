@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    {{-- Banner slider (admin: Banners) --}}
    @if($banners->count())
        <section class="relative overflow-hidden bg-neutral-900 text-white">
            <div id="banner-slider" class="relative">
                @foreach($banners as $banner)
                    <div class="banner-slide {{ $loop->first ? '' : 'hidden' }} relative">
                        <img src="{{ $banner->imageUrl() }}" alt="{{ $banner->title }}" class="w-full h-[320px] md:h-[420px] object-cover opacity-70">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
                            <h2 class="text-3xl md:text-5xl font-bold tracking-tight mb-3">{{ $banner->title }}</h2>
                            @if($banner->subtitle)
                                <p class="text-neutral-200 mb-6 max-w-xl">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->link)
                                <a href="{{ $banner->link }}" class="bg-white text-neutral-900 px-8 py-3 rounded-full font-medium hover:bg-neutral-200 transition">
                                    {{ trans_db('shop.shop_now') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
                @if($banners->count() > 1)
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                        @foreach($banners as $i => $banner)
                            <button type="button" onclick="showSlide({{ $i }})" class="banner-dot w-2.5 h-2.5 rounded-full {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}"></button>
                        @endforeach
                    </div>
                    <script>
                        let slideIdx = 0;
                        const slides = document.querySelectorAll('.banner-slide');
                        const dots = document.querySelectorAll('.banner-dot');
                        function showSlide(i) {
                            slideIdx = (i + slides.length) % slides.length;
                            slides.forEach((s, k) => s.classList.toggle('hidden', k !== slideIdx));
                            dots.forEach((d, k) => {
                                d.classList.toggle('bg-white', k === slideIdx);
                                d.classList.toggle('bg-white/40', k !== slideIdx);
                            });
                        }
                        setInterval(() => showSlide(slideIdx + 1), 6000);
                    </script>
                @endif
            </div>
        </section>
    @endif

    {{-- Hero --}}
    <section class="relative bg-neutral-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 py-20 md:py-28 grid md:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-neutral-500 mb-4">{{ trans_db('shop.welcome') }}</p>
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight leading-tight mb-4">{{ trans_db('shop.hero_title') }}</h1>
                <p class="text-neutral-500 text-lg mb-8">{{ trans_db('shop.hero_subtitle') }}</p>
                <a href="{{ route('products.index') }}"
                   class="inline-block bg-neutral-900 text-white px-8 py-3.5 rounded-full font-medium hover:bg-neutral-700 transition">
                    {{ trans_db('shop.shop_now') }}
                </a>
            </div>
            <div class="relative">
                <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-neutral-200 to-neutral-300 flex items-center justify-center overflow-hidden">
                    @if($featured->first()?->image)
                        <img src="{{ $featured->first()->image }}" alt="" class="w-full h-full object-cover">
                    @else
                        <span class="text-8xl">🛍️</span>
                    @endif
                </div>
                <div class="absolute -bottom-5 {{ app()->getLocale() === 'ur' ? 'right-6' : '-left-5' }} bg-white rounded-xl shadow-xl px-5 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-xl">✓</div>
                    <div class="text-sm">
                        <div class="font-semibold">{{ trans_db('shop.in_stock') }}</div>
                        <div class="text-neutral-500">{{ $featured->count() + $latest->count() }} {{ trans_db('shop.products') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Category tiles --}}
    @if($categories->count())
        <section class="max-w-7xl mx-auto px-4 py-14">
            <div class="flex items-end justify-between mb-6">
                <h2 class="text-2xl font-bold tracking-tight">{{ trans_db('shop.shop_by_category') }}</h2>
                <a href="{{ route('products.index') }}" class="text-sm underline underline-offset-4 hover:opacity-60">{{ trans_db('shop.view_all') }}</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group">
                        <div class="aspect-square rounded-xl bg-neutral-100 flex items-center justify-center text-4xl overflow-hidden group-hover:shadow-lg transition">
                            @if($category->image)
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            @else
                                🗂️
                            @endif
                        </div>
                        <div class="mt-2 text-sm font-medium text-center group-hover:underline">{{ $category->name }}</div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured products --}}
    <section class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-end justify-between mb-6">
            <h2 class="text-2xl font-bold tracking-tight">{{ trans_db('shop.featured_products') }}</h2>
            <a href="{{ route('products.index') }}" class="text-sm underline underline-offset-4 hover:opacity-60">{{ trans_db('shop.view_all') }}</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8">
            @foreach($featured as $product)
                @include('products.card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- Latest products --}}
    <section class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold tracking-tight mb-6">{{ trans_db('shop.latest_products') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8">
            @foreach($latest as $product)
                @include('products.card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- Value props --}}
    <section class="border-t border-neutral-200 mt-10">
        <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-3xl mb-2">🚚</div>
                <div class="font-semibold">{{ trans_db('shop.standard_shipping') }}</div>
                <div class="text-sm text-neutral-500">Free over $50</div>
            </div>
            <div>
                <div class="text-3xl mb-2">💵</div>
                <div class="font-semibold">{{ trans_db('shop.cash_on_delivery') }}</div>
                <div class="text-sm text-neutral-500">Pay at your door</div>
            </div>
            <div>
                <div class="text-3xl mb-2">↩️</div>
                <div class="font-semibold">Easy returns</div>
                <div class="text-sm text-neutral-500">7-day return policy</div>
            </div>
        </div>
    </section>
@endsection
