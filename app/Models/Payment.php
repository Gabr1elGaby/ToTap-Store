<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_gateway', 'transaction_id', 'amount', 
        'status', 'payment_method', 'paid_at', 'raw_response'
    ];

    protected $casts = [
        'raw_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order() { return $this->belongsTo(Order::class); }
}
