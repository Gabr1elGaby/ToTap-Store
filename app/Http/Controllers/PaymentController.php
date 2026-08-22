<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function page($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        return view('checkout.payment', compact('order'));
    }

    public function status($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        return response()->json(['status' => $order->payment_status]);
    }

    public function simulate(Request $request, $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        
        // Simulate sending a webhook to our own API
        $payload = [
            'order_id' => $order->order_number,
            'transaction_status' => 'settlement',
            'transaction_id' => 'TRX-' . time(),
            'gross_amount' => $order->amount,
            'payment_type' => 'bank_transfer',
            'signature_key' => hash('sha512', $order->order_number . '200' . $order->amount . env('PAYMENT_GATEWAY_SECRET', 'secret'))
        ];

        // Instead of using Http::post which causes a deadlock in PHP's single-threaded built-in server,
        // we simulate the webhook by directly calling the WebhookController.
        $webhookRequest = \Illuminate\Http\Request::create('/api/payment/webhook', 'POST', $payload);
        $webhookController = new \App\Http\Controllers\Api\WebhookController();
        $webhookController->handle($webhookRequest);

        return redirect()->route('payment.success', $order->order_number);
    }

    public function success($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        return view('checkout.success', compact('order'));
    }
}
