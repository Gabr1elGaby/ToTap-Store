<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class InvoiceHelper
{
    /**
     * Convert month number (1-12) to Roman numerals (I - XII)
     */
    public static function getRomanMonth($month = null): string
    {
        $m = (int) ($month ?? date('n'));
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[$m] ?? 'I';
    }

    /**
     * Format: INV/TOPUP/TTS/001/VIII/2026
     * Resets sequence to 001 every year
     */
    public static function generateTopUpInvoice(): string
    {
        $year = date('Y');
        $monthRoman = self::getRomanMonth(date('n'));

        // Query transactions in current year that have invoice_number or ID starting with INV/TOPUP/TTS/
        $latest = DB::table('transactions')
            ->whereYear('created_at', $year)
            ->where(function ($q) {
                $q->where('id', 'LIKE', 'INV/TOPUP/TTS/%')
                  ->orWhere('id', 'LIKE', 'TRX-%');
            })
            ->orderBy('created_at', 'desc')
            ->first();

        $nextNum = 1;
        if ($latest) {
            if (isset($latest->invoice_number) && preg_match('/INV\/TOPUP\/TTS\/(\d+)\//', $latest->invoice_number, $m1)) {
                $nextNum = (int)$m1[1] + 1;
            } elseif (preg_match('/INV\/TOPUP\/TTS\/(\d+)\//', $latest->id, $m2)) {
                $nextNum = (int)$m2[1] + 1;
            } else {
                $count = DB::table('transactions')->whereYear('created_at', $year)->count();
                $nextNum = $count + 1;
            }
        } else {
            $count = DB::table('transactions')->whereYear('created_at', $year)->count();
            $nextNum = max(1, $count + 1);
        }

        $sequence = str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        return "INV/TOPUP/TTS/{$sequence}/{$monthRoman}/{$year}";
    }

    /**
     * Format: INV/KASIR/TTS/001/VIII/2026
     * Resets sequence to 001 every year specifically for Kasir (POS)
     */
    public static function generateKasirInvoice(): string
    {
        $year = date('Y');
        $monthRoman = self::getRomanMonth(date('n'));

        $latest = DB::table('orders')
            ->whereYear('created_at', $year)
            ->where('order_number', 'LIKE', 'INV/KASIR/TTS/%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNum = 1;
        if ($latest && !empty($latest->order_number)) {
            if (preg_match('/INV\/KASIR\/TTS\/(\d+)\//', $latest->order_number, $matches)) {
                $nextNum = (int)$matches[1] + 1;
            } else {
                $count = DB::table('orders')
                    ->whereYear('created_at', $year)
                    ->where('order_number', 'LIKE', 'INV/KASIR/TTS/%')
                    ->count();
                $nextNum = $count + 1;
            }
        } else {
            $count = DB::table('orders')
                ->whereYear('created_at', $year)
                ->where('order_number', 'LIKE', 'INV/KASIR/TTS/%')
                ->count();
            $nextNum = max(1, $count + 1);
        }

        $sequence = str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        return "INV/KASIR/TTS/{$sequence}/{$monthRoman}/{$year}";
    }

    /**
     * Format: INV/CV/TTS/001/VIII/2026
     * Resets sequence to 001 every year
     */
    public static function generateCvInvoice(): string
    {
        $year = date('Y');
        $monthRoman = self::getRomanMonth(date('n'));

        $latest = DB::table('cvs')
            ->whereYear('created_at', $year)
            ->whereNotNull('invoice_number')
            ->orderBy('id', 'desc')
            ->first();

        $nextNum = 1;
        if ($latest && !empty($latest->invoice_number)) {
            if (preg_match('/INV\/CV\/TTS\/(\d+)\//', $latest->invoice_number, $matches)) {
                $nextNum = (int)$matches[1] + 1;
            } else {
                $count = DB::table('cvs')->whereYear('created_at', $year)->count();
                $nextNum = $count + 1;
            }
        } else {
            $count = DB::table('cvs')->whereYear('created_at', $year)->count();
            $nextNum = max(1, $count + 1);
        }

        $sequence = str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        return "INV/CV/TTS/{$sequence}/{$monthRoman}/{$year}";
    }

    /**
     * Format: DEP/TTS/001/VIII/2026
     * Resets sequence to 001 every year
     */
    public static function generateDepositInvoice(): string
    {
        $year = date('Y');
        $monthRoman = self::getRomanMonth(date('n'));

        try {
            $latest = DB::table('deposits')
                ->whereYear('created_at', $year)
                ->orderBy('created_at', 'desc')
                ->first();

            $nextNum = 1;
            if ($latest && !empty($latest->id)) {
                if (preg_match('/DEP\/TTS\/(\d+)\//', $latest->id, $matches)) {
                    $nextNum = (int)$matches[1] + 1;
                } else {
                    $count = DB::table('deposits')->whereYear('created_at', $year)->count();
                    $nextNum = $count + 1;
                }
            } else {
                $count = DB::table('deposits')->whereYear('created_at', $year)->count();
                $nextNum = max(1, $count + 1);
            }
        } catch (\Exception $e) {
            $nextNum = 1;
        }

        $sequence = str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        return "DEP/TTS/{$sequence}/{$monthRoman}/{$year}";
    }
}
