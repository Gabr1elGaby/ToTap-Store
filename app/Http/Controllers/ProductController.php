<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'demo_url' => 'nullable|url',
        ]);
        
        $data['is_active'] = $request->has('is_active');
        $data['slug'] = Str::slug($data['name']);
        
        Product::create($data);

        $this->purgeCache();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'demo_url' => 'nullable|url',
        ]);
        
        $data['is_active'] = $request->has('is_active');
        $data['slug'] = Str::slug($data['name']);
        
        $product->update($data);

        $this->purgeCache();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $this->purgeCache();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function purgeCache()
    {
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
        $vDir = storage_path('framework/views');
        if (is_dir($vDir)) {
            foreach (glob($vDir . '/*') as $f) {
                if (is_file($f) && basename($f) !== '.gitignore') @unlink($f);
            }
        }
    }
}
