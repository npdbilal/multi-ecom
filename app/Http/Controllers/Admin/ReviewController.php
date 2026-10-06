<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product', 'user')->latest()->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Toggle approval.
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        return back()->with('success', trans_db('admin.saved'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', trans_db('admin.deleted'));
    }
}
