<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['product_id', 'name', 'price', 'price_normal', 'duration_days', 'user_limit', 'transaction_limit', 'is_active'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_features');
    }
}
