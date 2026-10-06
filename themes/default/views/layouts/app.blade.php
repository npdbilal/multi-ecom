<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .product-card img { transition: transform .4s ease; }
        .product-card:hover img { transform: scale(1.05); }
        [dir="rtl"] .rtl-flip { transform: scaleX(-1); }
    </style>
</head>
<body class="bg-white text-neutral-900 font-sans antialiased min-h-screen flex flex-col">

    @php
        // Cart badge count for the header.
        $cartCount = 0;
        try {
            $attrs = auth()->check() ? ['user_id' => auth()->id()] : ['session_id' => session()->getId(), 'user_id' => null];
            $cartCount = \App\Models\Cart::where($attrs)->sum('quantity');
        } catch (\Throwable $e) { $cartCount = 0; }
    @endphp

    {{-- Announcement bar --}}
    <div class="bg-neutral-900 text-white text-center text-xs tracking-wide py-2 px-4">
        {{ trans_db('shop.announcement', null, []) !== 'shop.announcement' ? trans_db('shop.announcement') : 'Free shipping on orders over $50' }}
    </div>

    {{-- Header --}}
    <header class="border-b border-neutral-200 sticky top-0 bg-white/95 backdrop-blur z-40">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">{{ config('app.name') }}</a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:opacity-60">{{ trans_db('shop.home') }}</a>
                <a href="{{ route('products.index') }}" class="hover:opacity-60">{{ trans_db('shop.products') }}</a>
                @foreach(($availableLanguages->count() ? \App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(3)->get() : []) as $navCat)
                    <a href="{{ route('products.index', ['category' => $navCat->slug]) }}" class="hover:opacity-60">{{ $navCat->name }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-4">
                <a href="{{ route('products.index') }}" class="hover:opacity-60" title="{{ trans_db('shop.search') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </a>

                @if($availableLanguages->count() > 1)
                    <div class="relative group">
                        <button class="text-xs border border-neutral-300 rounded-full px-2 py-1 hover:border-neutral-900">{{ strtoupper($currentLanguage?->code ?? app()->getLocale()) }}</button>
                        <div class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} hidden group-hover:block bg-white border border-neutral-200 rounded-lg shadow-xl py-1 min-w-[150px] mt-1">
                            @foreach($availableLanguages as $lang)
                                <a href="{{ route('language.switch', $lang->code) }}"
                                   class="block px-4 py-2 text-sm hover:bg-neutral-100 {{ $lang->code === app()->getLocale() ? 'font-bold' : '' }}">
                                    {{ $lang->native_name ?: $lang->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @auth
                    <a href="{{ route('orders.index') }}" class="hover:opacity-60" title="{{ trans_db('shop.orders') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                @else
                    <a href="{{ route('login') }}" class="hover:opacity-60" title="{{ trans_db('shop.login') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative hover:opacity-60" title="{{ trans_db('shop.cart') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-neutral-900 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-neutral-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">
            <div>
                <div class="font-bold text-base mb-3">{{ config('app.name') }}</div>
                <p class="text-neutral-500">{{ trans_db('shop.welcome') }}</p>
            </div>
            <div>
                <div class="font-semibold mb-3">{{ trans_db('shop.products') }}</div>
                <ul class="space-y-2 text-neutral-500">
                    <li><a href="{{ route('products.index') }}" class="hover:text-neutral-900">{{ trans_db('shop.view_all') }}</a></li>
                    @foreach(\App\Models\Category::where('is_active', true)->orderBy('sort_order')->take(4)->get() as $fcat)
                        <li><a href="{{ route('products.index', ['category' => $fcat->slug]) }}" class="hover:text-neutral-900">{{ $fcat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <div class="font-semibold mb-3">{{ trans_db('shop.orders') }}</div>
                <ul class="space-y-2 text-neutral-500">
                    <li><a href="{{ route('cart.index') }}" class="hover:text-neutral-900">{{ trans_db('shop.cart') }}</a></li>
                    <li><a href="{{ route('checkout.index') }}" class="hover:text-neutral-900">{{ trans_db('shop.checkout') }}</a></li>
                    @auth
                        <li><a href="{{ route('orders.index') }}" class="hover:text-neutral-900">{{ trans_db('shop.orders') }}</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-neutral-900">{{ trans_db('shop.login') }}</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <div class="font-semibold mb-3">{{ trans_db('shop.language') }}</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($availableLanguages as $lang)
                        <a href="{{ route('language.switch', $lang->code) }}"
                           class="border rounded-full px-3 py-1 text-xs {{ $lang->code === app()->getLocale() ? 'bg-neutral-900 text-white border-neutral-900' : 'hover:border-neutral-900' }}">
                            {{ $lang->native_name ?: $lang->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="border-t border-neutral-200">
            <div class="max-w-7xl mx-auto px-4 py-4 text-xs text-neutral-400 flex justify-between">
                <span>© {{ date('Y') }} {{ config('app.name') }}</span>
                <span>Powered by MultiEcom</span>
            </div>
        </div>
    </footer>

</body>
</html>
