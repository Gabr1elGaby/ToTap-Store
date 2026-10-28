<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Deposit;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteExpiredPendingInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:delete-expired-pending {--minutes=15 : Batas waktu kadaluwarsa dalam menit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus invoice pending (Topup, Aplikasi, Software, Deposit, CV) yang melebihi batas waktu (default 15 menit) agar tidak menumpuk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = (int) $this->option('minutes');
        if ($minutes <= 0) {
            $minutes = 15;
        }

        $cutoff = now()->subMinutes($minutes);

        // 1. Hapus transaksi Top Up Game & Aplikasi Premium yang pending / waiting / unpaid
        $deletedTransactions = Transaction::whereIn('status', ['pending', 'waiting', 'unpaid', 'UNPAID', 'PENDING'])
            ->where('created_at', '<=', $cutoff)
            ->delete();

        // 2. Hapus tagihan Deposit saldo yang pending / waiting / unpaid
        $deletedDeposits = Deposit::whereIn('status', ['pending', 'waiting', 'unpaid', 'UNPAID', 'PENDING'])
            ->where('created_at', '<=', $cutoff)
            ->delete();

        // 3. Hapus Order Software SaaS yang payment_status nya pending / unpaid
        $deletedOrders = Order::whereIn('payment_status', ['pending', 'unpaid', 'waiting', 'UNPAID', 'PENDING'])
            ->where('created_at', '<=', $cutoff)
            ->delete();

        // 4. Hapus pesanan CV yang statusnya UNPAID / pending
        $deletedCvs = 0;
        if (\Illuminate\Support\Facades\Schema::hasTable('cvs')) {
            $deletedCvs = DB::table('cvs')
                ->whereIn('status', ['unpaid', 'UNPAID', 'pending', 'PENDING'])
                ->where('created_at', '<=', $cutoff)
                ->delete();
        }

        $totalDeleted = $deletedTransactions + $deletedDeposits + $deletedOrders + $deletedCvs;

        if ($totalDeleted > 0) {
            Log::info("Pembersihan Tagihan Pending (> {$minutes} menit): {$deletedTransactions} transaksi, {$deletedDeposits} deposit, {$deletedOrders} order software, {$deletedCvs} pesanan CV dihapus.");
        }

        $this->info("Berhasil menghapus {$totalDeleted} tagihan pending yang berumur lebih dari {$minutes} menit.");
        return 0;
    }
}
