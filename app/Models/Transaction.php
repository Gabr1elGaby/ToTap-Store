<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    // We are using UUIDs
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    
    public function gameProduct()
    {
        return $this->belongsTo(GameProduct::class);
    }
}
