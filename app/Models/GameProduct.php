<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'product_code',
        'name',
        'price_modal',
        'price_sell', 'is_promo', 'price_normal',
        'status',
        'provider'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
