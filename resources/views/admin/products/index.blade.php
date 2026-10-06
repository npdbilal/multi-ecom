@extends('layouts.admin')

@section('title', trans_db('admin.products'))

@section('content')
    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ {{ trans_db('admin.add_new') }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.name') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.price') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.stock') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-2 font-semibold">{{ $product->name }}<div class="text-xs text-gray-400">{{ $product->category?->name }}</div></td>
                        <td class="px-4 py-2">${{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-2">{{ $product->stock }}</td>
                        <td class="px-4 py-2">{{ $product->is_active ? trans_db('admin.active') : trans_db('admin.inactive') }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600">{{ trans_db('admin.edit') }}</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
