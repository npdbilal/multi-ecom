@extends('layouts.app')

@section('title', 'My Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold tracking-tight">My Products</h1>
        <a href="{{ route('vendor.products.create') }}" class="bg-neutral-900 text-white px-5 py-2.5 rounded-full text-sm font-medium">+ Add Product</a>
    </div>

    <div class="border border-neutral-200 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="text-left px-4 py-3">Name</th>
                    <th class="text-left px-4 py-3">Price</th>
                    <th class="text-left px-4 py-3">Stock</th>
                    <th class="text-left px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3">${{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-3">{{ $product->stock }}</td>
                        <td class="px-4 py-3">{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3"><a href="{{ route('vendor.products.edit', $product) }}" class="underline underline-offset-2">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
