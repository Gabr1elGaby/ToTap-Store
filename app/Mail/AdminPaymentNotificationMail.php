<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPaymentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $type; // 'transaction' (game/app) or 'order' (software saas)

    /**
     * Create a new message instance.
     */
    public function __construct($item, string $type = 'transaction')
    {
        $this->item = $item;
        $this->type = $type;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        if ($this->type === 'order') {
            $invoiceNumber = $this->item->order_number ?? $this->item->id;
            $productName = $this->item->product->name ?? 'Software SaaS';
            $amount = 'Rp' . number_format($this->item->amount ?? 0, 0, ',', '.');
            $subject = "💰 [PEMBAYARAN DITERIMA] Software: {$productName} ({$invoiceNumber}) - {$amount}";
        } else {
            $invoiceNumber = $this->item->id;
            $productName = $this->item->gameProduct->name ?? ($this->item->product->name ?? 'Produk Digital');
            $gameName = $this->item->game->name ?? 'Game / Aplikasi';
            $amount = 'Rp' . number_format($this->item->amount ?? 0, 0, ',', '.');
            $subject = "💰 [PEMBAYARAN DITERIMA] {$gameName} ({$invoiceNumber}) - {$amount}";
        }

        return $this->subject($subject)
                    ->view('emails.admin_payment_notification');
    }
}
