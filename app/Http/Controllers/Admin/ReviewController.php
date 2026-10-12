<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::query()
            ->with('product')
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggle(Review $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        return back()->with('status', $review->is_approved ? 'Review disetujui.' : 'Review disembunyikan.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('status', 'Review dihapus.');
    }
}
