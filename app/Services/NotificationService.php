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
            // 1. Tentukan target email customer secara tepat & cerdas:
            // Jika target_field_1 merupakan email valid (misal pada pembelian Canva, ChatGPT, Akun Premium),
            // prioritaskan kirim langsung ke email yang diisi di form tersebut!
            $recipientEmail = null;
            $rawField1 = trim($transaction->target_field_1 ?? '');

            if (!empty($rawField1) && filter_var($rawField1, FILTER_VALIDATE_EMAIL)) {
                $recipientEmail = $rawField1;
            } 
            // Jika bukan email (misal ID game pada Top Up Game), gunakan email akun ToTap Store yang terdaftar saat login
            elseif ($transaction->user && !empty($transaction->user->email) && filter_var($transaction->user->email, FILTER_VALIDATE_EMAIL)) {
                $recipientEmail = $transaction->user->email;
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
