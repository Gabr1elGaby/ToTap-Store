<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerReview;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerReview::latest();

        // Filter by Rating
        if ($request->filled('rating') && in_array($request->rating, [1, 2, 3, 4, 5])) {
            $query->where('rating', $request->rating);
        }

        // Search Keyword
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('customer_name', 'like', "%{$q}%")
                    ->orWhere('customer_contact', 'like', "%{$q}%")
                    ->orWhere('product_name', 'like', "%{$q}%")
                    ->orWhere('order_id', 'like', "%{$q}%")
                    ->orWhere('review_text', 'like', "%{$q}%");
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        // Overall Stats
        $totalReviews = CustomerReview::count();
        $avgRating = $totalReviews > 0 ? round(CustomerReview::avg('rating'), 1) : 5.0;

        // Rating Counts Breakdown
        $ratingCounts = [
            5 => CustomerReview::where('rating', 5)->count(),
            4 => CustomerReview::where('rating', 4)->count(),
            3 => CustomerReview::where('rating', 3)->count(),
            2 => CustomerReview::where('rating', 2)->count(),
            1 => CustomerReview::where('rating', 1)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'totalReviews', 'avgRating', 'ratingCounts'));
    }

    public function destroy(CustomerReview $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Ulasan berhasil dihapus.');
    }
}
