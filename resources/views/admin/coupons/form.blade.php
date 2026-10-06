@extends('layouts.admin')

@section('title', isset($coupon) ? trans_db('admin.edit') : trans_db('admin.add_new'))

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST" class="space-y-4">
            @csrf
            @if(isset($coupon)) @method('PUT') @endif

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.code') }} *</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required class="w-full border rounded px-3 py-2 mt-1 font-mono uppercase">
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.type') }} *</label>
                    <select name="type" class="w-full border rounded px-3 py-2 mt-1">
                        <option value="percent" {{ old('type', $coupon->type ?? '') == 'percent' ? 'selected' : '' }}>{{ trans_db('admin.percent') }}</option>
                        <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>{{ trans_db('admin.fixed') }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.value') }} *</label>
                    <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value ?? '') }}" required class="w-full border rounded px-3 py-2 mt-1">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.min_order') }}</label>
                    <input type="number" step="0.01" name="min_order" value="{{ old('min_order', $coupon->min_order ?? 0) }}" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.max_uses') }}</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses ?? '') }}" placeholder="∞" class="w-full border rounded px-3 py-2 mt-1">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.valid_from') }}</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($coupon) && $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="text-sm text-gray-600">{{ trans_db('admin.valid_until') }}</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at', isset($coupon) && $coupon->ends_at ? $coupon->ends_at->format('Y-m-d\TH:i') : '') }}" class="w-full border rounded px-3 py-2 mt-1">
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}> {{ trans_db('admin.active') }}</label>

            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">{{ trans_db('admin.save') }}</button>
                <a href="{{ route('admin.coupons.index') }}" class="border px-6 py-2 rounded">{{ trans_db('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
