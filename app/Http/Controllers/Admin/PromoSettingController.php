<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\PromoHelper;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PromoSettingController extends Controller
{
    public function index()
    {
        PromoHelper::ensureSchema();
        $settings = PromoHelper::getSettings();
        $todayCheck = PromoHelper::isDayPromoActiveToday();
        $dayNames = PromoHelper::$dayNames;

        // Statistics
        $totalDiscountGiven = 0;
        $totalDiscountOrders = 0;
        if (\Illuminate\Support\Facades\Schema::hasColumn('transactions', 'discount_amount')) {
            $totalDiscountOrders = Transaction::where('discount_amount', '>', 0)->count();
            $totalDiscountGiven = (float) Transaction::where('discount_amount', '>', 0)->sum('discount_amount');
        }

        $availableCategories = PromoHelper::$availableCategories;

        return view('admin.promos.index', compact('settings', 'todayCheck', 'dayNames', 'availableCategories', 'totalDiscountOrders', 'totalDiscountGiven'));
    }

    public function update(Request $request)
    {
        PromoHelper::ensureSchema();

        $request->validate([
            'promo_first_user_title'        => 'nullable|string|max:100',
            'promo_first_user_type'         => 'required|in:percent,fixed',
            'promo_first_user_value'        => 'required|numeric|min:0',
            'promo_first_user_max_discount' => 'nullable|numeric|min:0',
            'promo_first_user_min_spend'    => 'nullable|numeric|min:0',
            'promo_first_user_categories'   => 'nullable|array',

            'promo_day_title'               => 'nullable|string|max:100',
            'promo_day_days'                => 'nullable|array',
            'promo_day_days.*'              => 'integer|between:0,6',
            'promo_day_type'                => 'required|in:percent,fixed',
            'promo_day_value'               => 'required|numeric|min:0',
            'promo_day_max_discount'        => 'nullable|numeric|min:0',
            'promo_day_min_spend'           => 'nullable|numeric|min:0',
            'promo_day_categories'          => 'nullable|array',
        ]);

        // 1. First Time User Discount
        Setting::set('promo_first_user_active', $request->has('promo_first_user_active') ? '1' : '0');
        Setting::set('promo_first_user_title', trim($request->input('promo_first_user_title') ?: 'Diskon Spesial Pengguna Baru'));
        Setting::set('promo_first_user_type', $request->input('promo_first_user_type', 'percent'));
        Setting::set('promo_first_user_value', (string)(float)$request->input('promo_first_user_value', 10));
        Setting::set('promo_first_user_max_discount', (string)(float)$request->input('promo_first_user_max_discount', 0));
        Setting::set('promo_first_user_min_spend', (string)(float)$request->input('promo_first_user_min_spend', 0));

        $firstCats = $request->input('promo_first_user_categories', ['all']);
        if (empty($firstCats)) $firstCats = ['all'];
        Setting::set('promo_first_user_categories', json_encode(array_values((array)$firstCats)));

        // 2. Day-of-Week Recurring Promo
        Setting::set('promo_day_active', $request->has('promo_day_active') ? '1' : '0');
        Setting::set('promo_day_title', trim($request->input('promo_day_title') ?: 'Promo Hari Spesial'));
        
        $selectedDays = $request->input('promo_day_days', []);
        $cleanDays = array_values(array_map('intval', (array)$selectedDays));
        Setting::set('promo_day_days', json_encode($cleanDays));

        Setting::set('promo_day_type', $request->input('promo_day_type', 'percent'));
        Setting::set('promo_day_value', (string)(float)$request->input('promo_day_value', 5));
        Setting::set('promo_day_max_discount', (string)(float)$request->input('promo_day_max_discount', 0));
        Setting::set('promo_day_min_spend', (string)(float)$request->input('promo_day_min_spend', 0));

        $dayCats = $request->input('promo_day_categories', ['all']);
        if (empty($dayCats)) $dayCats = ['all'];
        Setting::set('promo_day_categories', json_encode(array_values((array)$dayCats)));

        return back()->with('success', 'Pengaturan Diskon Pengguna Baru & Promo Hari Spesial berhasil diperbarui dan aktif otomatis!');
    }

    public function simulate(Request $request)
    {
        $amount = (float) $request->input('amount', 50000);
        $email = trim($request->input('email', ''));
        $category = trim($request->input('category', 'all'));

        $user = null;
        if (!empty($email)) {
            $user = User::where('email', $email)->orWhere('phone_number', $email)->first();
        }

        $result = PromoHelper::calculateDiscount($user, $amount, $category);
        $isFirstTime = $user ? PromoHelper::isFirstTimeUser($user) : null;
        $dayCheck = PromoHelper::isDayPromoActiveToday();

        return response()->json([
            'success'       => true,
            'user_found'    => (bool) $user,
            'user_name'     => $user ? $user->name : null,
            'is_first_time' => $isFirstTime,
            'today_day'     => $dayCheck['day_name'],
            'day_active'    => $dayCheck['active'],
            'category'      => $category,
            'calculation'   => $result,
        ]);
    }
}
