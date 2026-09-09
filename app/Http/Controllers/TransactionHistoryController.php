<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Top Up Game Transactions
        $topups = Transaction::with(['game', 'gameProduct'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(15);

        // Software / License Orders
        $orders = Order::with(['product', 'plan'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact('topups', 'orders'));
    }

    public function invoice(string $id)
    {
        $user = Auth::user();

        // Cari di Top Up Transactions
        $transaction = Transaction::with(['game', 'gameProduct', 'user'])
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('invoice_number', $id);
            })
            ->where(function ($q) use ($user) {
                if ($user->role !== 'superadmin') {
                    $q->where('user_id', $user->id);
                }
            })
            ->first();

        if ($transaction) {
            if ((empty($transaction->provider_sn) || $transaction->status === 'processing') && !empty($transaction->provider_trx_id)) {
                try {
                    $vipService = app(\App\Services\VipResellerService::class);
                    $statusRes = $vipService->checkOrderStatus($transaction->provider_trx_id);
                    if (isset($statusRes['result']) && $statusRes['result'] === true && !empty($statusRes['data'])) {
                        $pData = is_array($statusRes['data']) && isset($statusRes['data'][0]) ? $statusRes['data'][0] : $statusRes['data'];
                        $sn = $pData['sn'] ?? ($pData['note'] ?? ($pData['info'] ?? ($pData['informasi'] ?? ($pData['message'] ?? null))));
                        $pStatus = strtolower($pData['status'] ?? '');
                        
                        $updateData = [];
                        if (!empty($sn)) {
                            $updateData['provider_sn'] = $sn;
                        }
                        if ($pStatus === 'success') {
                            $updateData['status'] = 'success';
                        } elseif ($pStatus === 'error' || $pStatus === 'failed') {
                            $updateData['status'] = 'failed';
                        }

                        if (!empty($updateData)) {
                            $transaction->update($updateData);
                            $transaction->refresh();
                        }
                    }
                } catch (\Throwable $e) {}
            }

            return view('transactions.invoice', [
                'type' => 'topup',
                'data' => $transaction,
            ]);
        }

        // Atau cari di Orders (Software / POS)
        $order = Order::with(['product', 'plan', 'user'])
            ->where('order_number', $id)
            ->where(function ($q) use ($user) {
                if ($user->role !== 'superadmin') {
                    $q->where('user_id', $user->id);
                }
            })
            ->firstOrFail();

        return view('transactions.invoice', [
            'type' => 'order',
            'data' => $order,
        ]);
    }
}
