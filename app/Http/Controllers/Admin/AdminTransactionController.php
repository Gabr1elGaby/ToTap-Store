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

        // CV Builder Orders
        $cvOrdersQuery = \Illuminate\Support\Facades\DB::table('cvs')
            ->join('cv_templates', 'cvs.template_id', '=', 'cv_templates.id')
            ->select('cvs.*', 'cv_templates.name as template_name', 'cv_templates.price', 'cv_templates.slug as template_slug')
            ->latest('cvs.created_at');

        if ($search) {
            $cvOrdersQuery->where(function($q) use ($search) {
                $q->where('cvs.invoice_number', 'like', "%{$search}%")
                  ->orWhere('cvs.name', 'like', "%{$search}%")
                  ->orWhere('cvs.email', 'like', "%{$search}%")
                  ->orWhere('cvs.phone', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $cvOrdersQuery->where('cvs.status', strtoupper($status));
        }

        $cvOrders = $cvOrdersQuery->paginate(20, ['*'], 'cv_page')->withQueryString();

        return view('admin.transactions.index', compact('transactions', 'orders', 'cvOrders', 'search', 'status'));
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

        $order = Order::with(['product', 'plan', 'user'])->where('order_number', $id)->first();
        if ($order) {
            return view('transactions.invoice', [
                'type' => 'order',
                'data' => $order,
                'isAdmin' => true,
            ]);
        }

        $cv = \Illuminate\Support\Facades\DB::table('cvs')
            ->join('cv_templates', 'cvs.template_id', '=', 'cv_templates.id')
            ->where('cvs.invoice_number', $id)
            ->orWhere('cvs.access_token', $id)
            ->orWhere('cvs.id', $id)
            ->select('cvs.*', 'cv_templates.name as template_name', 'cv_templates.price', 'cv_templates.slug as template_slug')
            ->first();

        if ($cv) {
            return view('checkout.cv', compact('cv'));
        }

        abort(404, 'Invoice tidak ditemukan.');
    }

    public function approveCv($id)
    {
        $cv = \Illuminate\Support\Facades\DB::table('cvs')
            ->where('id', $id)
            ->orWhere('access_token', $id)
            ->first();

        if (!$cv) {
            return redirect()->route('admin.transactions.index')->with('error', 'Data CV tidak ditemukan.');
        }

        \Illuminate\Support\Facades\DB::table('cvs')->where('id', $cv->id)->update([
            'status' => 'PAID',
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.transactions.index')->with('success', "Pembayaran CV untuk {$cv->name} (#{$cv->invoice_number}) BERHASIL DI-ACC! Link download PDF kini aktif untuk pengguna.");
    }

    public function rejectCv($id)
    {
        $cv = \Illuminate\Support\Facades\DB::table('cvs')
            ->where('id', $id)
            ->orWhere('access_token', $id)
            ->first();

        if (!$cv) {
            return redirect()->route('admin.transactions.index')->with('error', 'Data CV tidak ditemukan.');
        }

        \Illuminate\Support\Facades\DB::table('cvs')->where('id', $cv->id)->update([
            'status' => 'FAILED',
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.transactions.index')->with('error', "Pesanan CV (#{$cv->invoice_number}) telah ditolak/dibatalkan.");
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

    public function refundToBalance($id)
    {
        $transaction = Transaction::with(['user', 'game', 'gameProduct'])->findOrFail($id);

        if ($transaction->status === 'refunded') {
            return back()->with('info', "Transaksi #{$transaction->id} sudah berstatus Refund sebelumnya.");
        }

        if (!$transaction->user_id || !$transaction->user) {
            return back()->with('error', "Transaksi #{$transaction->id} tidak terhubung dengan akun user terdaftar, tidak dapat di-refund ke Saldo Akun.");
        }

        $user = $transaction->user;
        $amount = (float) $transaction->amount;

        // 1. Tambah Saldo User
        $user->increment('balance', $amount);

        // 2. Update status transaksi
        $transaction->update([
            'status' => 'refunded',
        ]);

        // 3. Kirim notifikasi WhatsApp via Fonnte jika nomor telepon user tersedia
        if (!empty($user->phone_number)) {
            try {
                $targetPhone = preg_replace('/[^0-9]/', '', $user->phone_number);
                if (str_starts_with($targetPhone, '0')) {
                    $targetPhone = '62' . substr($targetPhone, 1);
                }
                $token = \App\Models\Setting::get('fonnte_token', 'mEa7Y6Lq5u@U8b2Q8J1#');
                $gameName = $transaction->game->name ?? 'Game';
                $waMsg = "Halo *{$user->name}*,\n\n"
                       . "Pesanan Top Up Anda *#{$transaction->id}* ({$gameName}) telah dibatalkan oleh Admin.\n\n"
                       . "💰 Dana sebesar *Rp" . number_format($amount, 0, ',', '.') . "* telah berhasil *DIKEMBALIKAN KE SALDO AKUN TOTAP STORE* Anda.\n\n"
                       . "Saldo Anda saat ini: *Rp" . number_format($user->balance, 0, ',', '.') . "*\n"
                       . "Anda dapat menggunakan saldo ini untuk memesan kembali dengan User ID yang benar tanpa perlu transfer uang lagi.\n\n"
                       . "Kunjungi: https://totapstore.com\nTerima kasih!";

                \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $targetPhone,
                    'message' => $waMsg,
                ]);
            } catch (\Throwable $e) {}
        }

        return back()->with('success', "Dana sebesar Rp" . number_format($amount, 0, ',', '.') . " BERHASIL DI-REFUND ke Saldo Akun {$user->name}! Status transaksi kini 'Refunded'.");
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

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete();
            return back()->with('success', "Data transaksi #{$id} berhasil dihapus.");
        }

        $order = Order::where('order_number', $id)->first();
        if ($order) {
            $order->delete();
            return back()->with('success', "Data order software #{$id} berhasil dihapus.");
        }

        return back()->with('error', "Data transaksi tidak ditemukan.");
    }

    public function destroyCv($id)
    {
        try {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            foreach(['cv_educations', 'cv_experiences', 'cv_skills', 'cv_certificates', 'cv_projects', 'cv_internships', 'cv_organizations'] as $table) {
                try {
                    \Illuminate\Support\Facades\DB::table($table)->where('cv_id', $id)->delete();
                } catch (\Throwable $e) {}
            }
            \Illuminate\Support\Facades\DB::table('cvs')->where('id', $id)->orWhere('access_token', $id)->delete();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', "Data pesanan CV berhasil dihapus.");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', "Gagal menghapus data CV: " . $e->getMessage());
        }
    }

    public function clearAll(Request $request)
    {
        $type = $request->input('type', 'all');
        try {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            if ($type === 'topup' || $type === 'all') {
                try { \Illuminate\Support\Facades\DB::table('transactions')->delete(); } catch (\Throwable $e) {}
            }
            if ($type === 'software' || $type === 'all') {
                try { \Illuminate\Support\Facades\DB::table('orders')->delete(); } catch (\Throwable $e) {}
            }
            if ($type === 'cv' || $type === 'all') {
                foreach(['cv_educations', 'cv_experiences', 'cv_skills', 'cv_certificates', 'cv_projects', 'cv_internships', 'cv_organizations', 'cvs'] as $table) {
                    try {
                        \Illuminate\Support\Facades\DB::table($table)->delete();
                    } catch (\Throwable $e) {}
                }
            }
            
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', "Seluruh data riwayat transaksi lama (Top Up, Software, dan CV) berhasil dibersihkan!");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', "Gagal membersihkan data: " . $e->getMessage());
        }
    }
}
