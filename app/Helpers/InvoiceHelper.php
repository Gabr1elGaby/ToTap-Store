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
     * Format: INV/APKPRE/TTS/001/VIII/2026
     * Resets sequence to 001 every year specifically for Aplikasi Premium
     */
    public static function generateAppPremiumInvoice(): string
    {
        $year = date('Y');
        $monthRoman = self::getRomanMonth(date('n'));

        // Query transaksi aplikasi premium di tahun berjalan
        $latest = DB::table('transactions')
            ->whereYear('created_at', $year)
            ->where(function ($q) {
                $q->where('id', 'LIKE', 'INV/APKPRE/TTS/%')
                  ->orWhere('id', 'LIKE', 'INV/APKPRE/%');
            })
            ->orderBy('created_at', 'desc')
            ->first();

        $nextNum = 1;
        if ($latest) {
            if (isset($latest->invoice_number) && preg_match('/INV\/APKPRE\/(?:TTS\/)?(\d+)\//', $latest->invoice_number, $m1)) {
                $nextNum = (int)$m1[1] + 1;
            } elseif (preg_match('/INV\/APKPRE\/(?:TTS\/)?(\d+)\//', $latest->id, $m2)) {
                $nextNum = (int)$m2[1] + 1;
            } else {
                $count = DB::table('transactions')
                    ->whereYear('created_at', $year)
                    ->where('id', 'LIKE', 'INV/APKPRE%')
                    ->count();
                $nextNum = $count + 1;
            }
        } else {
            $count = DB::table('transactions')
                ->whereYear('created_at', $year)
                ->where('id', 'LIKE', 'INV/APKPRE%')
                ->count();
            $nextNum = max(1, $count + 1);
        }

        $sequence = str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        return "INV/APKPRE/TTS/{$sequence}/{$monthRoman}/{$year}";
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

    /**
     * Parse raw account credentials string from VIP Reseller / provider
     * into structured email, password, profile, pin, link, and generic key-value items.
     */
    public static function parseAccountCredentials(?string $raw): array
    {
        if (empty($raw)) {
            return [
                'is_structured' => false,
                'email' => null,
                'password' => null,
                'profile' => null,
                'link' => null,
                'items' => [],
                'raw' => '',
            ];
        }

        $raw = trim($raw);
        $items = [];
        $email = null;
        $password = null;
        $profile = null;
        $link = null;

        // 1. Cek apakah format menggunakan pemisah pipe (|), titik koma (;), atau baris baru (\n)
        if (str_contains($raw, '|') || str_contains($raw, ';') || str_contains($raw, "\n")) {
            $parts = preg_split('/[|;\n]+/', $raw);
            foreach ($parts as $part) {
                $part = trim($part);
                if (empty($part)) continue;

                if (preg_match('/^([a-zA-Z0-9_\-\s]+)\s*[:=]\s*(.+)$/u', $part, $m)) {
                    $key = strtoupper(trim($m[1]));
                    $val = trim($m[2]);

                    if (in_array($key, ['AKUN', 'ACCOUNT', 'EMAIL', 'USER', 'USERNAME', 'ID'])) {
                        // Cek jika nilai akun mengandung email--password
                        if (preg_match('/^([a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,10})\s*(?:[\x{2010}-\x{2015}\x{2212}—–\-]{1,6}|:\s*|\/|\s+-\s+)\s*(.+)$/u', $val, $em)) {
                            $email = trim($em[1]);
                            $password = trim($em[2]);
                            $items['Email / Akun'] = $email;
                            $items['Password'] = $password;
                        } else {
                            $email = $val;
                            $items['Email / Akun'] = $val;
                        }
                    } elseif (in_array($key, ['PASSWORD', 'PASS', 'PW'])) {
                        $password = $val;
                        $items['Password'] = $val;
                    } elseif (in_array($key, ['PROFILE', 'PROFIL', 'PIN', 'SLOT'])) {
                        $profile = $val;
                        $items['Profil / PIN'] = $val;
                    } elseif (in_array($key, ['KETENTUAN', 'RULES', 'PANDUAN', 'LINK', 'URL'])) {
                        if (preg_match('/(?:https?:\/\/|bit\.ly\/|tinyurl\.com\/)[^\s]+/i', $val, $urlMatch)) {
                            $url = $urlMatch[0];
                            if (!str_starts_with($url, 'http')) {
                                $url = 'https://' . $url;
                            }
                            $link = $url;
                            $items['Panduan / Rules'] = $url;
                        } else {
                            $items['Ketentuan'] = $val;
                        }
                    } else {
                        $items[ucwords(strtolower(trim($m[1])))] = $val;
                    }
                }
            }
        }

        // 2. Jika belum terurai oleh pipe/key-value, cek pola standard email--password atau email:password
        if (empty($items)) {
            // Ekstrak link terlebih dahulu jika ada
            if (preg_match('/(?:https?:\/\/|bit\.ly\/|tinyurl\.com\/)[^\s|,]+/i', $raw, $linkMatches)) {
                $linkUrl = $linkMatches[0];
                if (!str_starts_with($linkUrl, 'http')) {
                    $linkUrl = 'https://' . $linkUrl;
                }
                $link = $linkUrl;
            }

            $text = $raw;
            if ($link) {
                $text = str_replace([$link, 'https://' . $link, 'http://' . $link], '', $text);
            }
            $text = trim(preg_replace('/[|;,]+$/', '', trim($text)));
            $text = trim(preg_replace('/^[|;,]+/', '', trim($text)));
            $text = preg_replace('/^(AKUN|ACCOUNT|DATA|LOGIN)\s*[:=]\s*/i', '', $text);
            $text = trim($text);

            if (preg_match('/^([a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,10})\s*(?:[\x{2010}-\x{2015}\x{2212}—–\-]{1,6}|:\s*|\/|\s+-\s+)\s*(.+)$/u', $text, $matches)) {
                $email = trim($matches[1]);
                $password = trim($matches[2]);
                $items['Email / Akun'] = $email;
                $items['Password'] = $password;
            } elseif (preg_match('/^([^\s|:]+)\s*(?:[\x{2010}-\x{2015}\x{2212}—–\-]{1,6}|:\s*)\s*(.+)$/u', $text, $matches)) {
                $email = trim($matches[1]);
                $password = trim($matches[2]);
                $items['Email / Akun'] = $email;
                $items['Password'] = $password;
            } elseif ($link) {
                $items['Link Aktivasi'] = $link;
                if (!empty($text)) {
                    $items['Keterangan'] = $text;
                }
            }
        }

        if ($link && !isset($items['Link / Panduan']) && !isset($items['Panduan / Rules']) && !isset($items['Link Aktivasi'])) {
            $items['Link / Panduan'] = $link;
        }

        return [
            'is_structured' => !empty($items),
            'email' => $email,
            'password' => $password,
            'profile' => $profile,
            'link' => $link,
            'items' => $items,
            'raw' => $raw,
        ];
    }
}
