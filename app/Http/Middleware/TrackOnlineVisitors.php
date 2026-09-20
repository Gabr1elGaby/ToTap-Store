<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrackOnlineVisitors
{
    /**
     * Catat setiap pengunjung aktif ke Cache.
     * Key unik per session/IP, expire 5 menit.
     */
    public function handle(Request $request, Closure $next)
    {
        $sessionId = session()->getId() ?: $request->ip();
        $cacheKey  = 'online_visitor_' . md5($sessionId);

        Cache::put($cacheKey, [
            'ip'         => $request->ip(),
            'url'        => $request->path(),
            'last_seen'  => now()->toDateTimeString(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 100),
        ], now()->addMinutes(5));

        // Simpan daftar key aktif
        $keys   = Cache::get('online_visitor_keys', []);
        $keys[$cacheKey] = now()->addMinutes(5)->timestamp;

        // Bersihkan key yang sudah expire
        $keys = array_filter($keys, fn($exp) => $exp > now()->timestamp);
        Cache::put('online_visitor_keys', $keys, now()->addMinutes(10));

        return $next($request);
    }

    /**
     * Hitung jumlah pengunjung aktif saat ini.
     */
    public static function countOnline(): int
    {
        $keys = Cache::get('online_visitor_keys', []);
        $keys = array_filter($keys, fn($exp) => $exp > now()->timestamp);
        return count($keys);
    }
}
