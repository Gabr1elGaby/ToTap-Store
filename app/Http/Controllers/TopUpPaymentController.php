<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TopUpPaymentController extends Controller
{
    public function show($id)
    {
        $transaction = Transaction::with(['game', 'gameProduct'])->findOrFail($id);
        
        // Prevent showing already paid transactions on checkout page
        if ($transaction->status !== 'pending') {
            return redirect()->route('topup.index')->with('success', 'Transaksi ini sudah selesai atau dibatalkan.');
        }

        return view('topup.checkout', compact('transaction'));
    }

    // Since we're on localhost and can't receive Midtrans webhooks directly without ngrok,
    // we use a secure manual verification endpoint triggered by the frontend JS onSuccess.
    public function verify($id)
    {
        $transaction = Transaction::with('gameProduct')->findOrFail($id);

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY', 'Mid-server-ckZHwiXrG6K0f-NXv3ykujHi'));
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [],
        ];
        
        try {
            $midtransStatus = \Midtrans\Transaction::status($transaction->id);
            
            if ($midtransStatus->transaction_status == 'settlement' || $midtransStatus->transaction_status == 'capture') {
                if ($transaction->status === 'pending') {
                    // Update Status to Paid
                    $transaction->update([
                        'status' => 'paid',
                        'payment_method' => $midtransStatus->payment_type
                    ]);
                    
                    // TODO: TEMBAK API VIP RESELLER DISINI
                    // \App\Services\VipResellerService::order($transaction);
                    
                    return response()->json(['success' => true, 'message' => 'Pembayaran Berhasil!']);
                }
            }
            
            return response()->json(['success' => false, 'message' => 'Pembayaran belum lunas di Midtrans.']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal verifikasi: ' . $e->getMessage()]);
        }
    }
}
