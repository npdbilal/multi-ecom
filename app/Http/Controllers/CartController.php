<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = $this->items()->load('product');
        $subtotal = $items->sum(fn ($i) => $i->lineTotal());

        return view('cart.index', compact('items', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'nullable|integer|min:1|max:99']);
        $qty = $request->input('quantity', 1);

        abort_unless($product->is_active && $product->inStock(), 422, 'Product unavailable.');

        $attributes = $this->ownerAttributes();

        $item = Cart::where($attributes)->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $qty);
        } else {
            Cart::create($attributes + ['product_id' => $product->id, 'quantity' => $qty]);
        }

        return redirect()->route('cart.index')->with('success', trans_db('shop.added_to_cart'));
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
     * Merge guest cart into the user cart after login. Called by AuthController.
     */
    public static function mergeGuestCart(int $userId): void
    {
        Cart::where('session_id', session()->getId())
            ->whereNull('user_id')
            ->get()
            ->each(function ($item) use ($userId) {
                $existing = Cart::where('user_id', $userId)
                    ->where('product_id', $item->product_id)
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
