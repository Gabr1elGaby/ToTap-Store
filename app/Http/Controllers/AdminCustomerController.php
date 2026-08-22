<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminCustomerController extends Controller
{
    public function create()
    {
        $products = Product::with('plans')->where('is_active', true)->get();
        return view('admin.customers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'product_id' => 'required|exists:products,id',
            'plan_id' => 'required|exists:plans,id',
        ]);

        DB::beginTransaction();
        try {
            // Find or create the user in the main database
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'customer',
                ]);
            }

            $product = Product::findOrFail($request->product_id);
            $plan = Plan::findOrFail($request->plan_id);

            // Create subscription in the main database
            $sub = Subscription::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'plan_id' => $plan->id,
                'order_id' => null, // Manual
                'start_date' => now(),
                'end_date' => now()->addDays($plan->duration_days),
                'status' => 'ACTIVE'
            ]);

            // PROVISIONING TO KASIR SAAS
            if ($product->slug === 'sistem-kasir-pos') {
                try {
                    // Check if user already exists in kasir_saas DB
                    $kasirUser = DB::connection('kasir')->table('users')->where('email', $user->email)->first();
                    
                    if (!$kasirUser || $kasirUser->role !== 'admin') {
                        $storeId = DB::connection('kasir')->table('stores')->insertGetId([
                            'name' => 'Toko ' . $user->name,
                            'slug' => \Illuminate\Support\Str::slug('Toko ' . $user->name . ' ' . rand(1000, 9999)),
                            'subscription_ends_at' => $sub->end_date,
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        if (!$kasirUser) {
                            DB::connection('kasir')->table('users')->insert([
                                'store_id' => $storeId,
                                'name' => $user->name,
                                'email' => $user->email,
                                'password' => $user->password, // Uses the same hashed password
                                'role' => 'admin',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        } else {
                            DB::connection('kasir')->table('users')
                                ->where('id', $kasirUser->id)
                                ->update([
                                    'store_id' => $storeId,
                                    'role' => 'admin',
                                    'password' => $user->password,
                                    'updated_at' => now()
                                ]);
                        }

                        DB::connection('kasir')->table('subscriptions')->insert([
                            'store_id' => $storeId,
                            'plan_id' => null,
                            'start_date' => $sub->start_date,
                            'end_date' => $sub->end_date,
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Sync password
                        DB::connection('kasir')->table('users')
                            ->where('id', $kasirUser->id)
                            ->update(['password' => $user->password]);

                        if ($kasirUser->store_id) {
                            DB::connection('kasir')->table('subscriptions')
                                ->where('store_id', $kasirUser->store_id)
                                ->update([
                                    'end_date' => $sub->end_date,
                                    'status' => 'active',
                                    'updated_at' => now()
                                ]);

                            DB::connection('kasir')->table('stores')
                                ->where('id', $kasirUser->store_id)
                                ->update([
                                    'subscription_ends_at' => $sub->end_date,
                                    'updated_at' => now()
                                ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Kasir provisioning failed: ' . $e->getMessage());
                }
            }

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Customer dan Akses Aplikasi berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function revokeAccess(\App\Models\Subscription $subscription)
    {
        DB::beginTransaction();
        try {
            // Expire in website DB
            $subscription->update([
                'status' => 'revoked',
                'end_date' => now()->subDay(),
            ]);

            // Expire in Kasir DB
            if ($subscription->product->slug === 'sistem-kasir-pos') {
                $user = \App\Models\User::find($subscription->user_id);
                if ($user) {
                    $kasirUser = DB::connection('kasir')->table('users')->where('email', $user->email)->first();
                    if ($kasirUser && $kasirUser->store_id) {
                        DB::connection('kasir')->table('stores')
                            ->where('id', $kasirUser->store_id)
                            ->update([
                                'subscription_ends_at' => now()->subDay(),
                                'updated_at' => now()
                            ]);
                            
                        DB::connection('kasir')->table('subscriptions')
                            ->where('store_id', $kasirUser->store_id)
                            ->update([
                                'status' => 'revoked',
                                'end_date' => now()->subDay(),
                                'updated_at' => now()
                            ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Akses aplikasi untuk pelanggan tersebut berhasil dicabut (dikick).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencabut akses: ' . $e->getMessage());
        }
    }
}
