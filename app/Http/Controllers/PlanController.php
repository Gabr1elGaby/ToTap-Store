<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Plan;
use App\Models\Product;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('product')->latest()->paginate(10);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.plans.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_normal' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'user_limit' => 'nullable|integer',
            'transaction_limit' => 'nullable|integer',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        Plan::create($data);
        $this->purgeCache();

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        $products = Product::all();
        return view('admin.plans.edit', compact('plan', 'products'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_normal' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'user_limit' => 'nullable|integer',
            'transaction_limit' => 'nullable|integer',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $plan->update($data);
        $this->purgeCache();

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        $this->purgeCache();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
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
