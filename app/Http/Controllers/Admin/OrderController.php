<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:'.implode(',', Order::statuses()),
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $oldStatus = $order->status;
        $order->update($data);

        // Notify the customer when the order status actually changed.
        if ($data['status'] !== $oldStatus) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdated($order, $oldStatus));
            } catch (\Throwable) {
                // SMTP not configured — status is still updated.
            }
        }

        return back()->with('success', trans_db('admin.saved'));
    }
}
