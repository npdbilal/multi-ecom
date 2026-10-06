<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PluginManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', trans_db('shop.cart_empty'));
        }

        $subtotal = $items->sum(fn ($i) => $i->lineTotal());
        $shippingMethods = $this->shippingMethods($subtotal);
        $paymentMethods = $this->paymentMethods();

        return view('checkout.index', compact('items', 'subtotal', 'shippingMethods', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|string',
            'shipping_method' => 'required|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $items->sum(fn ($i) => $i->lineTotal());
        $shippingCost = $this->resolveShippingCost($request->shipping_method, $subtotal);
        $total = $subtotal + $shippingCost;

        $order = DB::transaction(function () use ($request, $items, $subtotal, $shippingCost, $total) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_PENDING,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount' => 0,
                'total' => $total,
                'currency' => 'USD',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => [
                    'address' => $request->address,
                    'city' => $request->city,
                    'country' => $request->country,
                ],
                'notes' => $request->notes,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'vendor_id' => $item->product->vendor_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $item->lineTotal(),
                ]);

                // Decrease stock.
                $item->product->decrement('stock', $item->quantity);
            }

            CartController::clearForOwner();

            return $order;
        });

        // Let plugins react (e.g. MultiVendor splits commissions, COD marks payable).
        PluginManager::doAction('checkout.completed', $order);

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }

    // ------------------------------------------------------------------

    protected function cartItems()
    {
        $attributes = auth()->check()
            ? ['user_id' => auth()->id()]
            : ['session_id' => session()->getId(), 'user_id' => null];

        return Cart::where($attributes)->with('product')->get();
    }

    /**
     * Collect shipping methods. Plugins register theirs via the
     * "shipping.methods" filter-style action; core always has "standard".
     */
    protected function shippingMethods(float $subtotal): array
    {
        $methods = [
            'standard' => ['label' => trans_db('shop.standard_shipping'), 'cost' => 0],
        ];

        $collected = [];
        PluginManager::doAction('shipping.methods', $subtotal, $collected);

        return array_merge($methods, $collected);
    }

    protected function paymentMethods(): array
    {
        $methods = [];
        PluginManager::doAction('payment.methods', $methods);

        return $methods ?: ['cod' => ['label' => trans_db('shop.cash_on_delivery')]];
    }

    protected function resolveShippingCost(string $method, float $subtotal): float
    {
        return (float) ($this->shippingMethods($subtotal)[$method]['cost'] ?? 0);
    }
}
