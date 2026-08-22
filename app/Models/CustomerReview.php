<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
    use HasFactory;

    protected $table = 'customer_reviews';

    protected $fillable = [
        'order_id',
        'order_type',
        'user_id',
        'customer_name',
        'customer_contact',
        'product_name',
        'rating',
        'review_text',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
