<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\VipResellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TripayCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Jika dibuka via browser (GET request)
        if ($request->isMethod('get')) {
            return response()->json([
                'success' => true,
                'status' => 'active',
                'message' => 'TriPay Webhook Callback endpoint is active and listening for POST notifications.',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $json = $request->getContent();
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $privateKey = config('services.tripay.private_key', env('TRIPAY_PRIVATE_KEY', ''));

        if (!empty($privateKey)) {
            $signature = hash_hmac('sha256', $json, $privateKey);
            if ($signature !== (string)$callbackSignature) {
                return response()->json(['success' => false, 'message' => 'Invalid Signature'], 403);
            }
        }

        $data = json_decode($json, true);
        if (!$data || !isset($data['merchant_ref'])) {
            return response()->json(['success' => false, 'message' => 'Invalid Payload'], 400);
        }

        $orderId = $data['merchant_ref'];
        $status = strtoupper($data['status'] ?? '');

        $transaction = Transaction::where('id', $orderId)->first();
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        if ($status === 'PAID') {
            if ($transaction->status !== 'success' && $transaction->status !== 'processing') {
                $transaction->update([
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);

                // Eksekusi otomatis ke VIP Reseller
                try {
                    $product = $transaction->product;
                    $game = $transaction->game;
                    if ($product && $game) {
                        $vipService = app(VipResellerService::class);
                        if (method_exists($vipService, 'order')) {
                            $orderRes = $vipService->order(
                                $product->product_code,
                                $transaction->target_field_1,
                                $transaction->target_field_2,
                                $transaction->id
                            );
                        } else {
                            $orderRes = $vipService->createOrder(
                                $product->product_code,
                                $transaction->target_field_1,
                                $transaction->target_field_2 ?? ''
                            );
                        }

                        if (isset($orderRes['result']) && $orderRes['result'] === true) {
                            $transaction->update([
                                'status' => 'success',
                                'provider_order_id' => $orderRes['data']['trxid'] ?? null,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('TriPay Auto-Fulfillment Error: ' . $e->getMessage());
                }
            }
        } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
            $transaction->update(['status' => 'failed']);
        }

        return response()->json(['success' => true]);
    }
}
