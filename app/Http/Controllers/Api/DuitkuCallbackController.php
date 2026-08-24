<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\VipResellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DuitkuCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Jika dibuka via browser (GET request)
        if ($request->isMethod('get')) {
            return response()->json([
                'success' => true,
                'status' => 'active',
                'gateway' => 'Duitku',
                'message' => 'Duitku Webhook Callback endpoint is active and ready.',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $apiKey = config('services.duitku.api_key', env('DUITKU_API_KEY', ''));
        $merchantCode = $request->input('merchantCode');
        $amount = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId');
        $signature = $request->input('signature');
        $resultCode = $request->input('resultCode');
        $reference = $request->input('reference');

        // Validasi Signature MD5 jika apiKey ada
        if (!empty($apiKey)) {
            $expectedSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);
            if ($signature !== $expectedSignature) {
                Log::error("Duitku Invalid Signature: expected $expectedSignature, got $signature");
                return response('BAD SIGNATURE', 400);
            }
        }

        $transaction = Transaction::where('id', $merchantOrderId)->first();
        if (!$transaction) {
            return response('TRANSACTION NOT FOUND', 404);
        }

        // Jika Pembayaran Berhasil (resultCode '00')
        if ($resultCode == '00') {
            if ($transaction->status !== 'success' && $transaction->status !== 'processing') {
                $transaction->update([
                    'status' => 'processing',
                    'paid_at' => now(),
                    'payment_reference' => $reference,
                ]);

                // Eksekusi Otomatis ke VIP Reseller
                try {
                    $product = $transaction->product ?? $transaction->gameProduct;
                    $game = $transaction->game;
                    if ($product && $game) {
                        $vipService = app(VipResellerService::class);
                        $orderRes = $vipService->order(
                            $product->product_code,
                            $transaction->target_field_1,
                            $transaction->target_field_2,
                            $transaction->id
                        );

                        if (isset($orderRes['result']) && $orderRes['result'] === true) {
                            $transaction->update([
                                'status' => 'success',
                                'provider_order_id' => $orderRes['data']['trxid'] ?? null,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Duitku Auto-Fulfillment Error: ' . $e->getMessage());
                }
            }
        } else {
            $transaction->update(['status' => 'failed']);
        }

        return response('SUCCESS', 200);
    }
}
