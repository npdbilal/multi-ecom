@extends('layouts.admin')

@section('title', trans_db('admin.coupons'))

@section('content')
    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ {{ trans_db('admin.add_new') }}</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.code') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.type') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.value') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.min_order') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.used') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.valid_until') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.status') }}</th>
                    <th class="text-left px-4 py-2">{{ trans_db('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="px-4 py-2 font-mono font-semibold">{{ $coupon->code }}</td>
                        <td class="px-4 py-2">{{ $coupon->type === 'percent' ? trans_db('admin.percent') : trans_db('admin.fixed') }}</td>
                        <td class="px-4 py-2">{{ $coupon->label() }}</td>
                        <td class="px-4 py-2">${{ number_format($coupon->min_order, 2) }}</td>
                        <td class="px-4 py-2">{{ $coupon->used_count }}{{ $coupon->max_uses ? ' / '.$coupon->max_uses : '' }}</td>
                        <td class="px-4 py-2">{{ $coupon->ends_at?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $coupon->is_active ? trans_db('admin.active') : trans_db('admin.inactive') }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-indigo-600">{{ trans_db('admin.edit') }}</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('?')">@csrf @method('DELETE')
                                <button class="text-red-600">{{ trans_db('admin.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">—</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $coupons->links() }}</div>
@endsection
