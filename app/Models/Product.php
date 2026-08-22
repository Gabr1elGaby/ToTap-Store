<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'features', 'logo', 'demo_url', 'is_active'];

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
