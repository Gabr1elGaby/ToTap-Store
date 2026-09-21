<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TrackOnlineVisitors
{
    /**
     * Catat setiap pengunjung aktif ke Cache.
     * Expire 5 menit.
     */
    public function handle(Request $request, Closure $next)
    {
        // Abaikan request assets/api/debug internal/logout agar tidak memenuhi log
        if ($request->is('api/*', 'admin/debug/*', 'livewire/*', 'build/*', 'storage/*', 'logout')) {
            return $next($request);
        }

        $user = Auth::user();
        $ip   = $request->ip();

        // 1 IP = 1 Cache Key unik (mencegah duplikasi sama sekali)
        $cacheKey = 'online_visitor_ip_' . md5($ip);

        $ua = strtolower($request->userAgent() ?? '');
        $isMobile = str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone');

        $userName = $user ? $user->name . ' (' . ucfirst($user->role ?? 'user') . ')' : 'Pengunjung Umum';

        $path = $request->path();
        if ($path === '/' || $path === '') {
            $pageLabel = '🏠 Halaman Beranda Utama';
        } elseif (str_contains($path, 'topup/')) {
            $slug = str_replace('topup/', '', $path);
            $pageLabel = '🎮 Produk Top Up: ' . ucfirst(str_replace('-', ' ', $slug));
        } elseif (str_contains($path, 'login')) {
            $pageLabel = '🔑 Halaman Login';
        } elseif (str_contains($path, 'register')) {
            $pageLabel = '📝 Halaman Registrasi';
        } elseif (str_contains($path, 'aplikasi-premium')) {
            $pageLabel = '📺 Aplikasi Premium';
        } elseif (str_contains($path, 'software')) {
            $pageLabel = '💻 Software POS';
        } else {
            $pageLabel = '📄 /' . $path;
        }

        Cache::put($cacheKey, [
            'ip'          => $ip,
            'url'         => '/' . $path,
            'page_label'  => $pageLabel,
            'user_name'   => $userName,
            'is_logged_in'=> (bool) $user,
            'is_mobile'   => $isMobile,
            'timestamp'   => now()->timestamp,
            'last_seen'   => now()->toDateTimeString(),
        ], now()->addMinutes(5));

        // Simpan daftar key aktif
        $keys = Cache::get('online_visitor_keys', []);
        $keys[$cacheKey] = now()->addMinutes(5)->timestamp;

        // Bersihkan key yang sudah expire
        $keys = array_filter($keys, fn($exp) => $exp > now()->timestamp);
        Cache::put('online_visitor_keys', $keys, now()->addMinutes(10));

        return $next($request);
    }

    /**
     * Ambil rincian lengkap pengunjung online (1 IP = 1 Baris).
     */
    public static function getOnlineDetails(): array
    {
        $keys = Cache::get('online_visitor_keys', []);
        $now  = now()->timestamp;
        $keys = array_filter($keys, fn($exp) => $exp > $now);

        $byIp = [];
        foreach (array_keys($keys) as $cacheKey) {
            $data = Cache::get($cacheKey);
            if ($data && !empty($data['ip'])) {
                $ip = $data['ip'];
                if (!isset($byIp[$ip])) {
                    $byIp[$ip] = $data;
                } else {
                    $existing = $byIp[$ip];
                    // Utamakan data yang logged-in atau aktivitas terbaru
                    if ($data['is_logged_in'] && !$existing['is_logged_in']) {
                        $byIp[$ip] = $data;
                    } elseif ($data['timestamp'] >= $existing['timestamp']) {
                        if ($data['is_logged_in'] === $existing['is_logged_in']) {
                            $byIp[$ip] = $data;
                        }
                    }
                }
            }
        }

        $details = [];
        foreach ($byIp as $data) {
            $secondsAgo = max(0, $now - ($data['timestamp'] ?? $now));
            if ($secondsAgo < 10) {
                $timeAgo = 'Baru saja';
            } elseif ($secondsAgo < 60) {
                $timeAgo = $secondsAgo . ' detik lalu';
            } else {
                $timeAgo = floor($secondsAgo / 60) . ' mnt lalu';
            }
            $data['time_ago'] = $timeAgo;
            $details[] = $data;
        }

        // Urutkan dari yang paling baru aktif
        usort($details, fn($a, $b) => ($b['timestamp'] ?? 0) <=> ($a['timestamp'] ?? 0));

        return $details;
    }

    /**
     * Hitung jumlah pengunjung unik (berdasarkan IP unik).
     */
    public static function countOnline(): int
    {
        return count(static::getOnlineDetails());
    }
}
