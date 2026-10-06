<div class="product-card group">
    <a href="{{ route('products.show', $product) }}" class="block relative overflow-hidden rounded-xl bg-neutral-100 aspect-[4/5]">
        @if($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-5xl text-neutral-300">📦</div>
        @endif
        @if($product->discountPercent())
            <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">-{{ $product->discountPercent() }}%</span>
        @endif
        <span class="absolute bottom-3 left-3 right-3 opacity-0 group-hover:opacity-100 transition bg-neutral-900/90 text-white text-sm text-center py-2.5 rounded-full backdrop-blur">
            {{ trans_db('shop.add_to_cart') }}
        </span>
    </a>
    <div class="pt-3">
        <div class="text-xs text-neutral-400 uppercase tracking-wide">{{ $product->category?->name }}</div>
        <a href="{{ route('products.show', $product) }}" class="font-medium text-sm hover:underline line-clamp-1">{{ $product->name }}</a>
        <div class="flex items-center gap-2 mt-1">
            <span class="font-semibold">${{ number_format($product->price, 2) }}</span>
            @if($product->compare_price)
                <span class="text-sm text-neutral-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
            @endif
        </div>
    </div>
</div>
