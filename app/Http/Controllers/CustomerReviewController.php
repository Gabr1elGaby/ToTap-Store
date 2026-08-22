<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerReview;

class CustomerReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'nullable|string|max:100',
            'order_type' => 'nullable|string|in:topup,software,cv',
            'customer_name' => 'required|string|max:100',
            'customer_contact' => 'nullable|string|max:100',
            'product_name' => 'nullable|string|max:150',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate review for the same order_id if present
        if (!empty($validated['order_id'])) {
            $existing = CustomerReview::where('order_id', $validated['order_id'])->first();
            if ($existing) {
                $existing->update([
                    'rating' => $validated['rating'],
                    'review_text' => $validated['review_text'] ?? $existing->review_text,
                    'customer_name' => $validated['customer_name'] ?: $existing->customer_name,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Terima kasih! Ulasan Anda telah diperbarui.',
                    'review' => $existing,
                ]);
            }
        }

        $review = CustomerReview::create([
            'order_id' => $validated['order_id'] ?? null,
            'order_type' => $validated['order_type'] ?? 'topup',
            'user_id' => auth()->id(),
            'customer_name' => $validated['customer_name'],
            'customer_contact' => $validated['customer_contact'] ?? (auth()->user()?->email ?? null),
            'product_name' => $validated['product_name'] ?? 'Layanan ToTap Store',
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'] ?? null,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas ulasan dan masukan Anda! Kami terus berusaha memberikan layanan terbaik.',
            'review' => $review,
        ]);
    }
}
