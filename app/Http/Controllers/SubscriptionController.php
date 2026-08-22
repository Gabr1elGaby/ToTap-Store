<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Product;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function active($product_id)
    {
        $product = Product::findOrFail($product_id);

        $activeSub = Subscription::where('user_id', auth()->id())
            ->where('product_id', $product_id)
            ->where('status', 'ACTIVE')
            ->where('end_date', '>', now())
            ->with(['product', 'plan'])
            ->first();

        if (!$activeSub) {
            return redirect('/');
        }

        $daysLeft = (int) abs(now()->diffInDays(Carbon::parse($activeSub->end_date)));
        $endDate  = Carbon::parse($activeSub->end_date)->locale('id')->isoFormat('D MMMM YYYY');

        // Get demo URL for Kasir
        $demoUrl = $product->demo_url ?? null;

        return view('subscription.active', compact('activeSub', 'product', 'daysLeft', 'endDate', 'demoUrl'));
    }
}
