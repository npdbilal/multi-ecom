<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
            'revenue' => Order::where('payment_status', Order::PAYMENT_PAID)
                ->orWhere('status', Order::STATUS_DELIVERED)
                ->sum('total'),
        ];

        $recentOrders = Order::latest()->take(8)->get();
        $lowStock = Product::where('stock', '<', 10)->orderBy('stock')->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStock'));
    }
}
