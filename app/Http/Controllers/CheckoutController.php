<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Plan;
use App\Models\Order;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(Plan $plan)
    {
        // Cek apakah user sudah punya langganan aktif untuk produk ini
        if (auth()->check()) {
            $activeSub = \App\Models\Subscription::where('user_id', auth()->id())
                ->where('product_id', $plan->product_id)
                ->where('status', 'ACTIVE')
                ->where('end_date', '>', now())
                ->with(['product', 'plan'])
                ->first();

            if ($activeSub) {
                return redirect()->route('subscription.active', $plan->product_id)
                    ->with('info', 'Anda sudah memiliki langganan aktif untuk produk ini.');
            }
        }

        return view('checkout.show', compact('plan'));
    }

    public function process(Request $request, Plan $plan)
    {
        $request->validate([
            'email_choice' => 'required|in:own,other',
        ]);

        $generatedPassword = null;
        $targetUser = $request->user();

        if ($request->email_choice === 'other') {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
            ]);

            $targetUser = \App\Models\User::where('email', $request->email)->first();
            if (!$targetUser) {
                $generatedPassword = \Illuminate\Support\Str::random(10);
                $targetUser = \App\Models\User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($generatedPassword),
                    'role' => 'customer',
                ]);
            }
        }

        // Clear previous session data just in case
        session()->forget(['generated_password', 'checkout_email']);

        // Store generated password in session for display after payment
        if ($generatedPassword) {
            session(['generated_password' => $generatedPassword]);
            session(['checkout_email' => $targetUser->email]);
        }

        // 1. Create Order with Kasir Invoice format if Kasir POS
        $isKasir = false;
        if ($plan->product) {
            $prodName = strtolower($plan->product->name ?? '');
            $prodSlug = strtolower($plan->product->slug ?? '');
            if (str_contains($prodName, 'kasir') || str_contains($prodSlug, 'kasir')) {
                $isKasir = true;
            }
        }

        if ($isKasir) {
            $orderNumber = \App\Helpers\InvoiceHelper::generateKasirInvoice();
        } else {
            $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        }
        
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $targetUser->id,
            'product_id' => $plan->product_id,
            'plan_id' => $plan->id,
            'amount' => $plan->price,
            'payment_status' => 'PENDING',
            'order_status' => 'PENDING',
            'gateway' => 'manual', // default for now, could be qris/transfer
        ]);

        // 2. Redirect to simulated payment page
        return redirect()->route('payment.page', $order->order_number);
    }
}
