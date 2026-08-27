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

        // Jika transaksi memiliki provider_trx_id tapi provider_sn nya masih kosong,
        // otomatis tarik data akun / SN terbaru dari VIP Reseller secara real-time!
        if (empty($transaction->provider_sn) && !empty($transaction->provider_trx_id)) {
            try {
                $vipService = app(VipResellerService::class);
                $statusRes = $vipService->checkOrderStatus($transaction->provider_trx_id);
                if (isset($statusRes['result']) && $statusRes['result'] === true && !empty($statusRes['data'])) {
                    $pData = is_array($statusRes['data']) && isset($statusRes['data'][0]) ? $statusRes['data'][0] : $statusRes['data'];
                    $sn = $pData['sn'] ?? ($pData['note'] ?? null);
                    if (!empty($sn)) {
                        $transaction->update([
                            'provider_sn' => $sn,
                            'status' => 'success',
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Auto sync provider_sn on checkout view failed: ' . $e->getMessage());
            }
        }

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
                ]);

                // Eksekusi otomatis ke VIP Reseller
                try {
                    $product = $transaction->gameProduct;
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
                            $sn = $orderRes['data']['sn'] ?? ($orderRes['data']['note'] ?? null);
                            $transaction->update([
                                'status' => 'success',
                                'provider_trx_id' => $orderRes['data']['trxid'] ?? null,
                                'provider_sn' => $sn,
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
                    ]);

                    try {
                        $product = $transaction->gameProduct;
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
                                $sn = $orderRes['data']['sn'] ?? ($orderRes['data']['note'] ?? null);
                                $transaction->update([
                                    'status' => 'success',
                                    'provider_trx_id' => $orderRes['data']['trxid'] ?? null,
                                    'provider_sn' => $sn,
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
