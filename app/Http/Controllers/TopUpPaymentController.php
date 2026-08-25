<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\DuitkuService;
use App\Services\TripayService;
use App\Services\VipResellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopUpPaymentController extends Controller
{
    public function show($id)
    {
        $transaction = Transaction::with(['game', 'gameProduct'])->findOrFail($id);
        return view('topup.checkout', compact('transaction'));
    }

    public function verify($id)
    {
        $transaction = Transaction::with(['game', 'gameProduct'])->findOrFail($id);
        $paymentData = json_decode($transaction->snap_token, true);

        if ($transaction->status === 'success' || $transaction->status === 'paid') {
            return response()->json(['success' => true, 'message' => 'Pembayaran Berhasil!']);
        }

        // 1. Verifikasi via Duitku
        if (isset($paymentData['gateway']) && $paymentData['gateway'] === 'duitku') {
            $duitku = app(DuitkuService::class);
            $detail = $duitku->checkTransaction($transaction->id);
            
            if (isset($detail['statusCode']) && $detail['statusCode'] == '00') {
                $transaction->update([
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);

                // Eksekusi otomatis ke VIP Reseller
                try {
                    $product = $transaction->gameProduct;
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
                    Log::error('Duitku Verify Auto-Fulfillment Error: ' . $e->getMessage());
                }

                return response()->json(['success' => true, 'message' => 'Pembayaran Berhasil & Pesanan Sedang Diproses!']);
            }
        }

        // 2. Verifikasi via TriPay (Fallback)
        if (isset($paymentData['gateway']) && $paymentData['gateway'] === 'tripay' && !empty($paymentData['reference'])) {
            $tripay = app(TripayService::class);
            $detail = $tripay->getTransactionDetail($paymentData['reference']);
            
            if (isset($detail['success']) && $detail['success'] === true && isset($detail['data']['status'])) {
                $status = strtoupper($detail['data']['status']);
                if ($status === 'PAID') {
                    $transaction->update([
                        'status' => 'processing',
                        'paid_at' => now(),
                    ]);

                    try {
                        $product = $transaction->gameProduct;
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
                        Log::error('TriPay Verify Auto-Fulfillment Error: ' . $e->getMessage());
                    }

                    return response()->json(['success' => true, 'message' => 'Pembayaran Berhasil & Pesanan Sedang Diproses!']);
                }
            }
        }

        return response()->json(['success' => false, 'message' => 'Pembayaran belum terdeteksi. Silakan selesaikan pembayaran terlebih dahulu.']);
    }
}
