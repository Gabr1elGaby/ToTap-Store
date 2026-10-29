<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $productName = $this->transaction->gameProduct->name ?? 'Produk Digital';
        $gameName = $this->transaction->game->name ?? 'ToTap Store';
        $subject = "Pesanan Berhasil: {$gameName} - {$this->transaction->id}";

        return $this->subject($subject)
                    ->view('emails.order_success');
    }
}
