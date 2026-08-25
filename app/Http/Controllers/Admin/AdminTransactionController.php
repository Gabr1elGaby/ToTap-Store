<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Transaction::with(['game', 'gameProduct', 'user'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('target_field_1', 'like', "%{$search}%")
                  ->orWhere('target_field_2', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('game', function ($gq) use ($search) {
                      $gq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(20)->withQueryString();

        // Software / License Orders
        $orders = Order::with(['product', 'plan', 'user'])->latest()->paginate(20);

        return view('admin.transactions.index', compact('transactions', 'orders', 'search', 'status'));
    }

    public function invoice(string $id)
    {
        $transaction = Transaction::with(['game', 'gameProduct', 'user'])->where('id', $id)->first();

        if ($transaction) {
            return view('transactions.invoice', [
                'type' => 'topup',
                'data' => $transaction,
                'isAdmin' => true,
            ]);
        }

        $order = Order::with(['product', 'plan', 'user'])->where('order_number', $id)->firstOrFail();

        return view('transactions.invoice', [
            'type' => 'order',
            'data' => $order,
            'isAdmin' => true,
        ]);
    }

    public function approve($id)
    {
        $transaction = Transaction::with(['game', 'gameProduct', 'user'])->findOrFail($id);

        if ($transaction->status === 'success') {
            return back()->with('info', 'Transaksi ini sudah berstatus Sukses sebelumnya.');
        }

        $transaction->update(['status' => 'processing']);

        try {
            $product = $transaction->gameProduct;
            $game = $transaction->game;
            
            if ($product && $game) {
                $vipService = app(\App\Services\VipResellerService::class);
                $orderRes = $vipService->order(
                    $product->product_code,
                    $transaction->target_field_1,
                    $transaction->target_field_2,
                    $transaction->id
                );

                if (isset($orderRes['result']) && $orderRes['result'] === true) {
                    $transaction->update([
                        'status' => 'success',
                        'provider_trx_id' => $orderRes['data']['trxid'] ?? null,
                    ]);

                    return back()->with('success', "Pesanan #{$transaction->id} BERHASIL di-ACC! Diamond berhasil dikirim otomatis via API VIP Reseller.");
                } else {
                    $errMsg = $orderRes['message'] ?? 'API Provider gagal memproses pesanan.';
                    $transaction->update(['status' => 'paid']);
                    return back()->with('warning', "Pembayaran Diterima, namun API VIP Reseller membalas: \"{$errMsg}\". Anda dapat melakukan pengisian manual lalu klik 'Tandai Sukses Manual'.");
                }
            }
        } catch (\Exception $e) {
            $transaction->update(['status' => 'paid']);
            return back()->with('warning', "Pembayaran Diterima, namun terjadi kendala koneksi API: {$e->getMessage()}. Silakan kirim manual jika perlu.");
        }

        $transaction->update(['status' => 'success']);
        return back()->with('success', "Pesanan #{$transaction->id} berhasil disetujui.");
    }

    public function manualSuccess($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'status' => 'success',
            'provider_trx_id' => $transaction->provider_trx_id ?? 'MANUAL-ADMIN-' . time(),
        ]);

        return back()->with('success', "Pesanan #{$transaction->id} berhasil ditandai sebagai SUKSES secara manual!");
    }

    public function reject($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'status' => 'failed',
        ]);

        return back()->with('error', "Pesanan #{$transaction->id} telah dibatalkan / ditolak.");
    }
}
