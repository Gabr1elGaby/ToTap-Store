<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'game_id',
        'game_product_id',
        'target_id',
        'target_zone',
        'amount',
        'payment_method',
        'payment_status',
        'snap_token',
        'api_trx_id',
        'topup_status',
        'api_response'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function product()
    {
        return $this->belongsTo(GameProduct::class, 'game_product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
