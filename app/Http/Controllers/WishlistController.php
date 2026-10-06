<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $items = auth()->user()->wishlistItems()->with('product.category')->latest()->paginate(12);

        return view('wishlist.index', compact('items'));
    }

    /**
     * Toggle a product in the customer's wishlist.
     */
    public function toggle(Product $product)
    {
        $user = auth()->user();

        if ($user->hasInWishlist($product->id)) {
            $user->wishlistItems()->where('product_id', $product->id)->delete();
            $message = trans_db('shop.removed_from_wishlist');
        } else {
            $user->wishlistItems()->create(['product_id' => $product->id]);
            $message = trans_db('shop.added_to_wishlist');
        }

        return back()->with('success', $message);
    }

    public function destroy(Product $product)
    {
        auth()->user()->wishlistItems()->where('product_id', $product->id)->delete();

        return back()->with('success', trans_db('shop.removed_from_wishlist'));
    }
}
