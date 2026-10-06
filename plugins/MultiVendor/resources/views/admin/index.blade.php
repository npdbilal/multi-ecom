@extends('layouts.admin')

@section('title', 'Vendors')

@section('content')
    <div class="flex justify-between mb-4">
        <a href="{{ route('admin.vendors.settings') }}" class="border px-4 py-2 rounded text-sm">Commission Settings</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-2">Store</th>
                    <th class="text-left px-4 py-2">Owner</th>
                    <th class="text-left px-4 py-2">Status</th>
                    <th class="text-left px-4 py-2">Earnings</th>
                    <th class="text-left px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($vendors as $vendor)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $vendor->name }}</td>
                        <td class="px-4 py-3">{{ $vendor->user->email }}</td>
                        <td class="px-4 py-3"><span class="bg-gray-100 px-2 py-0.5 rounded text-xs">{{ ucfirst($vendor->status) }}</span></td>
                        <td class="px-4 py-3 font-bold">${{ number_format($vendor->totalEarnings(), 2) }}</td>
                        <td class="px-4 py-3 flex gap-2 flex-wrap">
                            @if($vendor->status === 'pending')
                                <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST">@csrf<button class="text-green-600">Approve</button></form>
                                <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST">@csrf<button class="text-red-600">Reject</button></form>
                            @endif
                            @if($vendor->status === 'approved')
                                <form action="{{ route('admin.vendors.suspend', $vendor) }}" method="POST">@csrf<button class="text-yellow-600">Suspend</button></form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $vendors->links() }}</div>
@endsection
