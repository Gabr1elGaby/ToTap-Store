<?php

namespace App\Services;

use App\Models\Transaction;
use App\Mail\OrderSuccessMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Kirim email konfirmasi sukses ke customer jika transaksi berstatus success.
     */
    public static function sendOrderSuccessNotification(Transaction $transaction)
    {
        try {
            // 1. Tentukan target email customer
            $recipientEmail = null;

            // Jika transaksi terkait user terdaftar yang punya email
            if ($transaction->user && !empty($transaction->user->email) && filter_var($transaction->user->email, FILTER_VALIDATE_EMAIL)) {
                $recipientEmail = $transaction->user->email;
            } 
            // Atau jika target_field_1 merupakan email (misalnya pembelian Canva, Akun Premium, dll)
            elseif (!empty($transaction->target_field_1) && filter_var($transaction->target_field_1, FILTER_VALIDATE_EMAIL)) {
                $recipientEmail = $transaction->target_field_1;
            }

            if (!$recipientEmail) {
                return false;
            }

            // 2. Pastikan Mailer telah dikonfigurasi dan bukan driver null
            Mail::to($recipientEmail)->send(new OrderSuccessMail($transaction));
            Log::info("Email notifikasi pesanan berhasil dikirim ke: {$recipientEmail} untuk Transaksi {$transaction->id}");
            return true;
        } catch (\Throwable $e) {
            // Jangan gagalkan alur transaksi utama jika SMTP sedang gangguan/belum disetting
            Log::error("Gagal mengirim email notifikasi transaksi {$transaction->id}: " . $e->getMessage());
            return false;
        }
    }
}
