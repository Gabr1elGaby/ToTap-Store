<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'product_id', 'plan_id', 'amount', 
        'payment_status', 'order_status', 'gateway', 'gateway_transaction_id', 'paid_at'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function plan() { return $this->belongsTo(Plan::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function subscription() { return $this->hasOne(Subscription::class); }
}
