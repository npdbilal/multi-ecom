<div class="product-card group">
    <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden rounded-xl bg-neutral-100 aspect-[4/5]">
        @php $cardImage = $product->displayImage(); @endphp
        @if($cardImage)
            <img src="{{ $cardImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-5xl text-neutral-300">📦</div>
        @endif
        @if($product->discountPercent())
            <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">-{{ $product->discountPercent() }}%</span>
        @endif
        @auth
            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3">
                @csrf
                <button title="{{ trans_db('shop.wishlist') }}"
                        class="w-9 h-9 rounded-full bg-white/90 backdrop-blur flex items-center justify-center shadow hover:scale-110 transition {{ auth()->user()->hasInWishlist($product->id) ? 'text-red-600' : 'text-neutral-400' }}">
                    <svg class="w-5 h-5" fill="{{ auth()->user()->hasInWishlist($product->id) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
            </form>
        @endauth
        <span class="absolute bottom-3 left-3 right-3 opacity-0 group-hover:opacity-100 transition bg-neutral-900/90 text-white text-sm text-center py-2.5 rounded-full backdrop-blur">
            {{ trans_db('shop.add_to_cart') }}
        </span>
    </a>
    <div class="pt-3">
        <div class="text-xs text-neutral-400 uppercase tracking-wide">{{ $product->category?->name }}</div>
        <a href="{{ route('products.show', $product) }}" class="font-medium text-sm hover:underline line-clamp-1">{{ $product->name }}</a>
        @if($product->reviewsCount())
            <div class="flex items-center gap-1 mt-1 text-xs text-neutral-500">
                <span class="text-amber-500">★</span>
                <span class="font-medium">{{ $product->averageRating() }}</span>
                <span>({{ $product->reviewsCount() }})</span>
            </div>
        @endif
        <div class="flex items-center gap-2 mt-1">
            <span class="font-semibold">${{ number_format($product->price, 2) }}</span>
            @if($product->compare_price)
                <span class="text-sm text-neutral-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
            @endif
        </div>
    </div>
</div>
