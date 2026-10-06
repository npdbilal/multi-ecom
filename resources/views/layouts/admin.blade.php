<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', trans_db('admin.dashboard')) — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex min-h-screen">
    <aside class="w-60 bg-gray-900 text-gray-200 flex-shrink-0">
        <div class="p-4 font-bold text-white text-lg">{{ config('app.name') }} <span class="text-xs font-normal text-gray-400">admin</span></div>
        <nav class="px-2 space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📊 {{ trans_db('admin.dashboard') }}</a>
            <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📦 {{ trans_db('admin.products') }}</a>
            <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🗂️ {{ trans_db('admin.categories') }}</a>
            <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🧾 {{ trans_db('admin.orders') }}</a>
            <a href="{{ route('admin.coupons.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🎟️ {{ trans_db('admin.coupons') }}</a>
            <a href="{{ route('admin.reviews.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">⭐ {{ trans_db('admin.reviews') }}</a>
            <a href="{{ route('admin.pages.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">📄 {{ trans_db('admin.pages') }}</a>
            <a href="{{ route('admin.banners.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🖼️ {{ trans_db('admin.banners') }}</a>
            <a href="{{ route('admin.media.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🗃️ {{ trans_db('admin.media') }}</a>
            @if(plugin_enabled('MultiVendor'))
                <a href="{{ route('admin.vendors.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🏪 Vendors</a>
            @endif
            <div class="pt-2 mt-2 border-t border-gray-700 text-xs text-gray-500 px-3">{{ trans_db('admin.settings') }}</div>
            <a href="{{ route('admin.languages.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🌐 {{ trans_db('admin.languages') }}</a>
            <a href="{{ route('admin.translations.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🔤 {{ trans_db('admin.translations') }}</a>
            <a href="{{ route('admin.themes.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🎨 {{ trans_db('admin.themes') }}</a>
            <a href="{{ route('admin.plugins.index') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🔌 {{ trans_db('admin.plugins') }}</a>
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded hover:bg-gray-800">🏪 {{ trans_db('admin.view_store') }}</a>
        </nav>
    </aside>

    <div class="flex-1">
        <header class="bg-white shadow-sm px-6 py-3 flex justify-between items-center">
            <h1 class="text-xl font-semibold">@yield('title', trans_db('admin.dashboard'))</h1>
            <div class="flex items-center gap-3 text-sm">
                @if($availableLanguages->count() > 1)
                    <div class="relative group">
                        <button class="border rounded px-2 py-1">🌐 {{ $currentLanguage?->code }}</button>
                        <div class="absolute right-0 hidden group-hover:block bg-white border rounded shadow-lg py-1">
                            @foreach($availableLanguages as $lang)
                                <a href="{{ route('language.switch', $lang->code) }}" class="block px-3 py-1.5 hover:bg-gray-100">{{ $lang->native_name ?: $lang->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <span>{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">@csrf
                    <button class="text-red-600">{{ trans_db('shop.logout') }}</button>
                </form>
            </div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded mb-4">
                    <ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
