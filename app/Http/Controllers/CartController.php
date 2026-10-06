<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CouponService $coupons)
    {
        $items = $this->items()->with('product', 'variant')->get();
        $subtotal = $items->sum(fn ($i) => $i->lineTotal());
        $coupon = $coupons->fromSession($subtotal);

        return view('cart.index', compact('items', 'subtotal', 'coupon'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:99',
            'product_variant_id' => 'nullable|exists:product_variants,id',
        ]);
        $qty = $request->input('quantity', 1);

        abort_unless($product->is_active, 422, 'Product unavailable.');

        $variant = null;
        if ($request->filled('product_variant_id')) {
            $variant = $product->activeVariants()->findOrFail($request->product_variant_id);
            abort_unless($variant->inStock(), 422, 'Variant out of stock.');
        } else {
            abort_unless($product->inStock(), 422, 'Product unavailable.');
        }

        $item = Cart::where($this->ownerAttributes())
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->first();

        if ($item) {
            $item->increment('quantity', $qty);
        } else {
            Cart::create($this->ownerAttributes() + [
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $qty,
            ]);
        }

        return redirect()->route('cart.index')->with('success', trans_db('shop.added_to_cart'));
    }

    /**
     * Apply a discount coupon code (stored in session).
     */
    public function applyCoupon(Request $request, CouponService $coupons)
    {
        $request->validate(['code' => 'required|string|max:50']);

        $subtotal = $this->items()->with('product', 'variant')->get()
            ->sum(fn ($i) => $i->lineTotal());

        $result = $coupons->validate($request->code, $subtotal);

        if (! $result['valid']) {
            return back()->with('error', $result['message']);
        }

        $coupons->apply($request->code);

        return back()->with('success', $result['message']);
    }

    public function removeCoupon(CouponService $coupons)
    {
        $coupons->clear();

        return back()->with('success', trans_db('shop.coupon_removed'));
    }

    public function update(Request $request, $rowId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        $item = $this->items()->findOrFail($rowId);
        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', trans_db('shop.cart_updated'));
    }

    public function remove($rowId)
    {
        $this->items()->findOrFail($rowId)->delete();

        return back()->with('success', trans_db('shop.removed_from_cart'));
    }

    /**
     * Cart rows belonging to the current visitor (user or session).
     */
    protected function items()
    {
        return Cart::where($this->ownerAttributes());
    }

    protected function ownerAttributes(): array
    {
        if (auth()->check()) {
            return ['user_id' => auth()->id()];
        }

        return ['session_id' => session()->getId(), 'user_id' => null];
    }

    /**
     * Merge guest cart into the user cart after login. Called by FirebaseAuthController.
     */
    public static function mergeGuestCart(int $userId): void
    {
        Cart::where('session_id', session()->getId())
            ->whereNull('user_id')
            ->get()
            ->each(function ($item) use ($userId) {
                $existing = Cart::where('user_id', $userId)
                    ->where('product_id', $item->product_id)
                    ->where('product_variant_id', $item->product_variant_id)
                    ->first();

                if ($existing) {
                    $existing->increment('quantity', $item->quantity);
                    $item->delete();
                } else {
                    $item->update(['user_id' => $userId, 'session_id' => null]);
                }
            });
    }

    public static function clearForOwner(): void
    {
        (new static)->items()->delete();
    }
}
