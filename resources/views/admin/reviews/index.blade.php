@extends('layouts.admin')

@section('title', trans_db('admin.reviews'))

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.product') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.customer') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.rating') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.comment') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($reviews as $review)
                    <tr>
                        <td class="px-4 py-2"><a href="{{ route('products.show', $review->product) }}" class="text-indigo-600" target="_blank">{{ $review->product->name }}</a></td>
                        <td class="px-4 py-2">{{ $review->user?->name ?? '—' }}</td>
                        <td class="px-4 py-2 text-amber-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                        <td class="px-4 py-2 max-w-xs truncate">{{ $review->comment }}</td>
                        <td class="px-4 py-2">{{ $review->is_approved ? trans_db('admin.active') : trans_db('admin.inactive') }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">@csrf
                                <button class="text-indigo-600">{{ $review->is_approved ? trans_db('admin.unapprove') : trans_db('admin.approve') }}</button>
                            </form>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">—</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $reviews->links() }}</div>
@endsection
