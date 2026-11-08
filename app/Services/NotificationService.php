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

    /**
     * Kirim notifikasi pembayaran masuk ke Email Admin (gabriel.gaby1277@gmail.com).
     * Berlaku untuk: Top Up Game, Aplikasi Premium, dan Software SaaS.
     */
    public static function sendAdminPaymentReceivedNotification($item, string $type = 'transaction')
    {
        try {
            $adminEmail = config('mail.admin_notification_email', env('ADMIN_NOTIFICATION_EMAIL', 'gabriel.gaby1277@gmail.com'));
            if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            Mail::to($adminEmail)->send(new \App\Mail\AdminPaymentNotificationMail($item, $type));
            $identifier = ($type === 'order') ? ($item->order_number ?? $item->id) : $item->id;
            Log::info("Email notifikasi pembayaran masuk berhasil dikirim ke Admin ({$adminEmail}) untuk {$type} #{$identifier}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email notifikasi ke admin: " . $e->getMessage());
            return false;
        }
    }
}
