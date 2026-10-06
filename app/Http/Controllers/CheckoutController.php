<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CouponService;
use App\Services\PluginManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index(CouponService $coupons)
    {
        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', trans_db('shop.cart_empty'));
        }

        $totals = $this->totals($items, $coupons);
        $shippingMethods = $this->shippingMethods($totals['subtotal']);
        $paymentMethods = $this->paymentMethods();
        $addresses = auth()->check() ? auth()->user()->addresses : collect();

        return view('checkout.index', array_merge(
            compact('items', 'shippingMethods', 'paymentMethods', 'addresses'),
            $totals
        ));
    }

    public function store(Request $request, CouponService $coupons)
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

        $totals = $this->totals($items, $coupons);
        $shippingCost = $this->resolveShippingCost($request->shipping_method, $totals['subtotal']);
        $grandTotal = $totals['taxable'] + $shippingCost + ($totals['tax_included'] ? 0 : $totals['tax']);

        $order = DB::transaction(function () use ($request, $items, $totals, $shippingCost, $grandTotal, $coupons) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_PENDING,
                'payment_method' => $request->payment_method,
                'subtotal' => $totals['subtotal'],
                'shipping_cost' => $shippingCost,
                'tax' => $totals['tax'],
                'discount' => $totals['discount'],
                'coupon_code' => $totals['coupon']?->code,
                'total' => $grandTotal,
                'currency' => setting('currency', 'USD'),
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
                    'product_variant_id' => $item->product_variant_id,
                    'variant_label' => $item->variant?->name,
                    'vendor_id' => $item->product->vendor_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->unitPrice(),
                    'total' => $item->lineTotal(),
                ]);

                // Decrease stock — variant stock when a variant was bought.
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                } else {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            $coupons->markUsed($totals['coupon']);
            CartController::clearForOwner();

            return $order;
        });

        // Let plugins react (e.g. MultiVendor splits commissions, COD marks payable).
        PluginManager::doAction('checkout.completed', $order);

        // Order confirmation email (queue-ready; never breaks checkout).
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));
        } catch (\Throwable) {
            // SMTP not configured — the order itself is already placed.
        }

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }

    // ------------------------------------------------------------------

    /**
     * Shared cart math: subtotal, coupon discount, taxable base, tax.
     *
     * Tax is driven by admin settings: tax_rate (%), tax_included toggle.
     *
     * @return array{subtotal: float, coupon: ?\App\Models\Coupon, discount: float, taxable: float, tax: float, tax_rate: float, tax_included: bool}
     */
    protected function totals($items, CouponService $coupons): array
    {
        $subtotal = (float) $items->sum(fn ($i) => $i->lineTotal());
        $coupon = $coupons->fromSession($subtotal);
        $taxable = max(0, $subtotal - $coupon['discount']);

        $taxRate = (float) setting('tax_rate', 0);
        $taxIncluded = filter_var(setting('tax_included', false), FILTER_VALIDATE_BOOLEAN);
        $tax = $taxIncluded ? 0 : round($taxable * $taxRate / 100, 2);

        return [
            'subtotal' => $subtotal,
            'coupon' => $coupon['coupon'],
            'discount' => $coupon['discount'],
            'taxable' => $taxable,
            'tax' => $tax,
            'tax_rate' => $taxRate,
            'tax_included' => $taxIncluded,
        ];
    }

    protected function cartItems()
    {
        $attributes = auth()->check()
            ? ['user_id' => auth()->id()]
            : ['session_id' => session()->getId(), 'user_id' => null];

        return Cart::where($attributes)->with('product', 'variant')->get();
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
