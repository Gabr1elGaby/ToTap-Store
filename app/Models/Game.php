<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'developer',
        'category',
        'thumbnail',
        'cover_image',
        'guide_text',
        'is_active',
        'requires_zone_id'
    ];

    public function products()
    {
        return $this->hasMany(GameProduct::class);
    }
}
