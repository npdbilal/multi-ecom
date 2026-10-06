@extends('layouts.app')

@section('title', 'Become a Vendor')

@section('content')
<div class="max-w-xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold tracking-tight text-center mb-4">Sell on {{ config('app.name') }}</h1>
    <p class="text-neutral-500 text-center mb-8">Open your store, list products, and earn with every sale.</p>

    @if($existing)
        <div class="border border-neutral-200 rounded-2xl p-8 text-center">
            <div class="text-4xl mb-3">🏪</div>
            <div class="font-semibold">{{ $existing->name }}</div>
            <div class="mt-2 text-sm">
                @if($existing->status === 'pending')
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">Under review — we will notify you soon.</span>
                @elseif($existing->status === 'approved')
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">Approved!</span>
                    <a href="{{ route('vendor.dashboard') }}" class="block mt-4 bg-neutral-900 text-white px-6 py-3 rounded-full">Go to Dashboard</a>
                @elseif($existing->status === 'rejected')
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full">Application not approved.</span>
                @else
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full">Suspended — contact support.</span>
                @endif
            </div>
        </div>
    @else
        <form action="{{ route('vendor.register') }}" method="POST" class="space-y-4 border border-neutral-200 rounded-2xl p-8">
            @csrf
            <div>
                <label class="text-sm text-neutral-600">Store Name</label>
                <input type="text" name="name" required class="w-full border border-neutral-300 rounded-xl px-4 py-3 mt-1 text-sm outline-none focus:border-neutral-900">
            </div>
            <div>
                <label class="text-sm text-neutral-600">About your store</label>
                <textarea name="description" rows="3" class="w-full border border-neutral-300 rounded-xl px-4 py-3 mt-1 text-sm outline-none focus:border-neutral-900"></textarea>
            </div>
            <div>
                <label class="text-sm text-neutral-600">Logo URL (optional)</label>
                <input type="text" name="logo" class="w-full border border-neutral-300 rounded-xl px-4 py-3 mt-1 text-sm outline-none focus:border-neutral-900">
            </div>
            <button class="w-full bg-neutral-900 text-white py-3.5 rounded-full font-medium">Submit Application</button>
        </form>
    @endif
</div>
@endsection
