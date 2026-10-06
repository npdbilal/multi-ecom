<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $user = auth()->user();

        // One review per customer per product — update the existing one.
        $review = Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => $user->id],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                // Auto-approve unless the admin requires moderation.
                'is_approved' => ! filter_var(setting('reviews_require_approval', true), FILTER_VALIDATE_BOOLEAN),
            ]
        );

        $message = $review->is_approved
            ? trans_db('shop.review_submitted')
            : trans_db('shop.review_pending_approval');

        return back()->with('success', $message);
    }
}
