<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $productId = null): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        // Optional: Check if user has ANY active subscription, or specific to a product
        $query = \App\Models\Subscription::where('user_id', $request->user()->id)
            ->where('status', 'ACTIVE')
            ->where('end_date', '>=', now());

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $hasSubscription = $query->exists();

        if (!$hasSubscription) {
            return redirect()->route('dashboard')->with('error', 'Anda harus memiliki langganan aktif untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}
