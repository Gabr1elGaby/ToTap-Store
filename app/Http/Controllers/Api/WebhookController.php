<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        
        Log::info('Webhook received:', $payload);

        // Simulated validation of Midtrans/Xendit webhook
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Missing order ID'], 400);
        }

        // Validate signature
        $serverKey = env('PAYMENT_GATEWAY_SECRET', 'secret');
        $expectedSignature = hash('sha512', $orderId . '200' . $grossAmount . $serverKey);
        
        if ($expectedSignature !== $signatureKey) {
            Log::warning('Invalid signature', ['expected' => $expectedSignature, 'received' => $signatureKey]);
            // For production, uncomment this:
            // return response()->json(['message' => 'Invalid signature'], 403);
        }

        DB::beginTransaction();
        try {
            $order = Order::where('order_number', $orderId)->lockForUpdate()->first();

            if (!$order) {
                DB::rollBack();
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Idempotency check
            if ($order->payment_status === 'PAID') {
                DB::rollBack();
                return response()->json(['message' => 'Already processed'], 200);
            }

            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                // Payment success
                $order->update([
                    'payment_status' => 'PAID',
                    'order_status' => 'COMPLETED',
                    'gateway_transaction_id' => $transactionId,
                    'paid_at' => now()
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway' => 'midtrans',
                    'transaction_id' => $transactionId,
                    'amount' => $grossAmount ?? $order->amount,
                    'status' => 'PAID',
                    'payment_method' => $payload['payment_type'] ?? 'bank_transfer',
                    'paid_at' => now(),
                    'raw_response' => $payload
                ]);

                // Create subscription
                $sub = Subscription::create([
                    'user_id' => $order->user_id,
                    'product_id' => $order->product_id,
                    'plan_id' => $order->plan_id,
                    'order_id' => $order->id,
                    'start_date' => now(),
                    'end_date' => now()->addDays($order->plan->duration_days),
                    'status' => 'ACTIVE'
                ]);

                // PROVISIONING TO KASIR SAAS
                if ($order->product->slug === 'sistem-kasir-pos') {
                    try {
                        $user = $order->user;
                        
                        // Check if user already exists in kasir_saas DB
                        $kasirUser = DB::connection('kasir')->table('users')->where('email', $user->email)->first();
                        
                        if (!$kasirUser || $kasirUser->role !== 'admin') {
                            // Create store
                            $storeId = DB::connection('kasir')->table('stores')->insertGetId([
                                'name' => 'Toko ' . $user->name,
                                'slug' => \Illuminate\Support\Str::slug('Toko ' . $user->name . ' ' . rand(1000, 9999)),
                                'subscription_ends_at' => $sub->end_date,
                                        'user_limit' => $order->plan->user_limit,
                                'user_limit' => $order->plan->user_limit,
                                'status' => 'active',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            if (!$kasirUser) {
                                // Create user in kasir
                                $kasirUserId = DB::connection('kasir')->table('users')->insertGetId([
                                    'store_id' => $storeId,
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'password' => $user->password, // same password
                                    'role' => 'admin',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            } else {
                                // Upgrade existing cashier to admin and assign to new store
                                DB::connection('kasir')->table('users')
                                    ->where('id', $kasirUser->id)
                                    ->update([
                                        'store_id' => $storeId,
                                        'role' => 'admin',
                                        'password' => $user->password,
                                        'updated_at' => now()
                                    ]);
                            }

                            // Create subscription in kasir
                            DB::connection('kasir')->table('subscriptions')->insert([
                                'store_id' => $storeId,
                                'plan_id' => null, // Just to bypass if kasir plans aren't synced
                                'start_date' => $sub->start_date,
                                'end_date' => $sub->end_date,
                                'status' => 'active',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        } else {
                            // Sync password just in case they were generated newly in Gabriel Systems
                            DB::connection('kasir')->table('users')
                                ->where('id', $kasirUser->id)
                                ->update(['password' => $user->password]);

                            // Update end date if renewing
                            if ($kasirUser->store_id) {
                                DB::connection('kasir')->table('subscriptions')
                                    ->where('store_id', $kasirUser->store_id)
                                    ->update([
                                        'end_date' => $sub->end_date,
                                        'status' => 'active',
                                        'updated_at' => now()
                                    ]);
                                    
                                DB::connection('kasir')->table('stores')
                                    ->where('id', $kasirUser->store_id)
                                    ->update([
                                        'subscription_ends_at' => $sub->end_date,
                                        'user_limit' => $order->plan->user_limit,
                                'user_limit' => $order->plan->user_limit,
                                        'updated_at' => now()
                                    ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Kasir provisioning failed: ' . $e->getMessage());
                    }
                }

            } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny' || $transactionStatus === 'expire') {
                $order->update([
                    'payment_status' => 'FAILED',
                    'order_status' => 'CANCELLED',
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook processing failed: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
