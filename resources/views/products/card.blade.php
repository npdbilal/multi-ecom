<a href="{{ route('products.show', $product) }}" class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md block">
    <div class="aspect-square bg-gray-200 flex items-center justify-center text-5xl">
        @if($product->image)
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            📦
        @endif
    </div>
    <div class="p-3">
        <div class="text-xs text-gray-500">{{ $product->category?->name }}</div>
        <div class="font-semibold text-sm truncate">{{ $product->name }}</div>
        <div class="flex items-center gap-2 mt-1">
            <span class="font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
            @if($product->compare_price)
                <span class="text-xs text-gray-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                <span class="text-xs bg-red-100 text-red-700 px-1 rounded">-{{ $product->discountPercent() }}%</span>
            @endif
        </div>
    </div>
</a>
