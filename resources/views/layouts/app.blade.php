<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [dir="rtl"] .rtl-flip { transform: scaleX(-1); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">{{ config('app.name') }}</a>

            <form action="{{ route('products.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ trans_db('shop.search') }}"
                       class="w-full border rounded-l px-3 py-2 text-sm">
                <button class="bg-indigo-600 text-white px-4 rounded-r text-sm">{{ trans_db('shop.search') === 'Search products...' ? 'Go' : '↗' }}</button>
            </form>

            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('products.index') }}" class="hover:text-indigo-600">{{ trans_db('shop.products') }}</a>
                <a href="{{ route('cart.index') }}" class="hover:text-indigo-600">🛒 {{ trans_db('shop.cart') }}</a>

                {{-- Language switcher --}}
                @if($availableLanguages->count() > 1)
                    <div class="relative group">
                        <button class="border rounded px-2 py-1">🌐 {{ $currentLanguage?->native_name ?? $currentLanguage?->name }}</button>
                        <div class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} hidden group-hover:block bg-white border rounded shadow-lg py-1 min-w-[140px]">
                            @foreach($availableLanguages as $lang)
                                <a href="{{ route('language.switch', $lang->code) }}"
                                   class="block px-3 py-1.5 hover:bg-gray-100 {{ $lang->code === app()->getLocale() ? 'font-bold' : '' }}">
                                    {{ $lang->native_name ?: $lang->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @auth
                    <a href="{{ route('orders.index') }}" class="hover:text-indigo-600">{{ trans_db('shop.orders') }}</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 font-semibold">{{ trans_db('admin.dashboard') }}</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">@csrf
                        <button class="hover:text-indigo-600">{{ trans_db('shop.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-indigo-600">{{ trans_db('shop.login') }}</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-3 py-1.5 rounded">{{ trans_db('shop.register') }}</a>
                @endauth
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded">{{ session('error') }}</div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col md:flex-row justify-between gap-4 text-sm">
            <div>
                <div class="font-bold text-white text-lg">{{ config('app.name') }}</div>
                <div>{{ trans_db('shop.welcome') }}</div>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('products.index') }}" class="hover:text-white">{{ trans_db('shop.products') }}</a>
                <a href="{{ route('cart.index') }}" class="hover:text-white">{{ trans_db('shop.cart') }}</a>
            </div>
        </div>
    </footer>

</body>
</html>
