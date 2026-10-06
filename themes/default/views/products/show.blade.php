@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Breadcrumbs --}}
    <nav class="text-xs text-neutral-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-neutral-900">{{ trans_db('shop.home') }}</a>
        <span class="mx-1">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-neutral-900">{{ trans_db('shop.products') }}</a>
        <span class="mx-1">/</span>
        <span class="text-neutral-900">{{ $product->name }}</span>
    </nav>

    <div class="grid md:grid-cols-2 gap-10 lg:gap-16">
        {{-- Gallery --}}
        <div>
            @php
                $gallery = $product->images->count() ? $product->images : collect();
                $mainImage = $gallery->first()?->url() ?? $product->image;
            @endphp
            <div class="rounded-2xl overflow-hidden bg-neutral-100 aspect-square">
                @if($mainImage)
                    <img id="main-image" src="{{ $mainImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-8xl text-neutral-300">📦</div>
                @endif
            </div>
            @if($gallery->count() > 1)
                <div class="grid grid-cols-5 gap-2 mt-2">
                    @foreach($gallery as $img)
                        <button type="button" onclick="document.getElementById('main-image').src='{{ $img->url() }}'"
                                class="rounded-lg overflow-hidden bg-neutral-100 aspect-square hover:ring-2 ring-neutral-900">
                            <img src="{{ $img->url() }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Details --}}
        <div>
            <div class="text-xs uppercase tracking-[0.2em] text-neutral-400 mb-2">{{ $product->category?->name }}</div>
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight">{{ $product->name }}</h1>

            @if($product->reviewsCount())
                <a href="#reviews" class="inline-flex items-center gap-1.5 mt-3 text-sm text-neutral-500 hover:text-neutral-900">
                    <span class="text-amber-500 text-base">{{ str_repeat('★', round($product->averageRating())) }}{{ str_repeat('☆', 5 - round($product->averageRating())) }}</span>
                    <span class="font-medium">{{ $product->averageRating() }}</span>
                    <span>({{ $product->reviewsCount() }} {{ trans_db('shop.reviews') }})</span>
                </a>
            @endif

            <div class="flex items-center gap-3 mt-4">
                <span class="text-2xl font-bold" id="price-display">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_price)
                    <span class="text-neutral-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                    <span class="bg-red-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">-{{ $product->discountPercent() }}%</span>
                @endif
            </div>

            @if($product->short_description)
                <p class="text-neutral-500 mt-4">{{ $product->short_description }}</p>
            @endif

            <div class="mt-3 text-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $product->inStock() ? 'bg-green-500' : 'bg-red-500' }}"></span>
                <span class="{{ $product->inStock() ? 'text-green-700' : 'text-red-600' }}">
                    {{ $product->inStock() ? trans_db('shop.in_stock') : trans_db('shop.out_of_stock') }}
                </span>
                @if($product->isLowStock() && $product->inStock())
                    <span class="text-amber-600 text-xs">· {{ trans_db('admin.low_stock') }}</span>
                @endif
            </div>

            @if($product->inStock())
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-6" id="add-to-cart-form">
                    @csrf
                    @if($product->activeVariants->count())
                        <label class="block text-sm font-medium mb-2">{{ trans_db('shop.select_variant') }}</label>
                        <div class="flex flex-wrap gap-2 mb-4" id="variant-picker">
                            @foreach($product->activeVariants as $variant)
                                <button type="button" data-variant="{{ $variant->id }}" data-price="{{ $variant->effectivePrice() }}"
                                        data-stock="{{ $variant->stock }}"
                                        class="variant-btn border border-neutral-300 rounded-full px-4 py-2 text-sm hover:border-neutral-900 {{ $loop->first ? 'border-neutral-900 ring-1 ring-neutral-900' : '' }}">
                                    {{ $variant->name }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="product_variant_id" id="variant-input" value="{{ $product->activeVariants->first()->id }}">
                    @endif
                    <div class="flex gap-3">
                        <div class="flex items-center border border-neutral-300 rounded-full">
                            <input type="number" name="quantity" value="1" min="1" max="99"
                                   class="w-16 text-center py-3 bg-transparent outline-none">
                        </div>
                        <button class="flex-1 bg-neutral-900 text-white rounded-full font-medium hover:bg-neutral-700 transition py-3">
                            {{ trans_db('shop.add_to_cart') }}
                        </button>
                        @auth
                            <button type="button" onclick="document.getElementById('wishlist-form').submit()"
                                    title="{{ trans_db('shop.wishlist') }}"
                                    class="w-[52px] h-[52px] flex-shrink-0 border {{ $inWishlist ? 'border-red-300 text-red-600' : 'border-neutral-300 text-neutral-400' }} rounded-full flex items-center justify-center hover:border-red-400">
                                <svg class="w-5 h-5" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </button>
                        @endauth
                    </div>
                </form>
                @auth
                    <form id="wishlist-form" action="{{ route('wishlist.toggle', $product) }}" method="POST" class="hidden">@csrf</form>
                @endauth
            @endif

            {{-- Accordions --}}
            <div class="mt-8 border-t border-neutral-200">
                <details class="border-b border-neutral-200 py-4 group" open>
                    <summary class="font-medium cursor-pointer list-none flex justify-between">
                        {{ trans_db('shop.description') }} <span class="text-neutral-400">+</span>
                    </summary>
                    <p class="text-sm text-neutral-500 mt-3 whitespace-pre-line">{{ $product->description }}</p>
                </details>
                <details class="border-b border-neutral-200 py-4">
                    <summary class="font-medium cursor-pointer list-none flex justify-between">
                        {{ trans_db('shop.shipping') }} <span class="text-neutral-400">+</span>
                    </summary>
                    <p class="text-sm text-neutral-500 mt-3">{{ trans_db('shop.standard_shipping') }} · {{ trans_db('shop.cash_on_delivery') }}</p>
                </details>
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    <div class="mt-16 max-w-3xl" id="reviews">
        <h2 class="text-2xl font-bold tracking-tight mb-6">{{ trans_db('shop.reviews') }} ({{ $product->reviewsCount() }})</h2>

        @if($reviews->count())
            <div class="space-y-6 mb-10">
                @foreach($reviews as $review)
                    <div class="border-b border-neutral-200 pb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 rounded-full bg-neutral-200 flex items-center justify-center font-semibold text-sm">
                                {{ strtoupper(substr($review->user?->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-sm">{{ $review->user?->name }}</div>
                                <div class="text-xs text-neutral-400">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                            <span class="ml-auto text-amber-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-neutral-600">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
            {{ $reviews->links() }}
        @else
            <p class="text-neutral-400 text-sm mb-10">{{ trans_db('shop.no_reviews') }}</p>
        @endif

        @auth
            <div class="bg-neutral-50 rounded-2xl p-6">
                <h3 class="font-semibold mb-4">{{ trans_db('shop.write_review') }}</h3>
                <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-2">{{ trans_db('shop.your_rating') }}</label>
                        <div class="flex gap-1 text-3xl" id="star-picker">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" data-star="{{ $i }}" class="star-btn text-neutral-300 hover:text-amber-400">★</button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="5" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">{{ trans_db('shop.your_review') }}</label>
                        <textarea name="comment" rows="3" class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-sm outline-none focus:border-neutral-900"></textarea>
                    </div>
                    <button class="bg-neutral-900 text-white rounded-full px-8 py-3 text-sm font-medium hover:bg-neutral-700">{{ trans_db('shop.submit_review') }}</button>
                </form>
            </div>
        @else
            <p class="text-sm text-neutral-500"><a href="{{ route('login') }}" class="underline underline-offset-4">{{ trans_db('shop.login') }}</a> to write a review.</p>
        @endauth
    </div>

    @if($related->count())
        <div class="mt-16">
            <h2 class="text-2xl font-bold tracking-tight mb-6">{{ trans_db('shop.related_products') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8">
                @foreach($related as $product)
                    @include('products.card', ['product' => $product])
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
(function () {
    // Variant picker: swap hidden input + displayed price.
    const variantBtns = document.querySelectorAll('.variant-btn');
    const variantInput = document.getElementById('variant-input');
    const priceDisplay = document.getElementById('price-display');
    variantBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            variantBtns.forEach(b => b.classList.remove('border-neutral-900', 'ring-1', 'ring-neutral-900'));
            btn.classList.add('border-neutral-900', 'ring-1', 'ring-neutral-900');
            variantInput.value = btn.dataset.variant;
            priceDisplay.textContent = '$' + parseFloat(btn.dataset.price).toFixed(2);
        });
    });

    // Star rating picker.
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');
    function paint(n) {
        stars.forEach(s => {
            s.classList.toggle('text-amber-400', s.dataset.star <= n);
            s.classList.toggle('text-neutral-300', s.dataset.star > n);
        });
    }
    stars.forEach(s => s.addEventListener('click', () => {
        ratingInput.value = s.dataset.star;
        paint(s.dataset.star);
    }));
    paint(5);
})();
</script>
@endsection
