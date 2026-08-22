<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class PublicProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->with(['plans' => function($q) {
            $q->where('is_active', true)->orderBy('price');
        }])->firstOrFail();

        return view('products.show', compact('product'));
    }
}
