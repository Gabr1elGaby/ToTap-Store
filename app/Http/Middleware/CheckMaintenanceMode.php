<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Bypass essential admin routes, login/logout, health check, and payment webhooks
        if (
            $request->is('admin*') ||
            $request->is('login*') ||
            $request->is('logout*') ||
            $request->is('up') ||
            $request->is('api/system-status') ||
            $request->is('api/tripay/*') ||
            $request->is('api/duitku/*') ||
            $request->is('api/payment/*')
        ) {
            return $next($request);
        }

        // 2. Allow Super Admin users who are logged in to browse the site freely
        if (auth()->check()) {
            $user = auth()->user();
            if (in_array(strtolower($user->role ?? ''), ['admin', 'superadmin', 'owner']) || !empty($user->is_admin)) {
                return $next($request);
            }
        }

        // 3. Check if maintenance mode is enabled in the database
        try {
            if (Schema::hasTable('settings')) {
                $setting = DB::table('settings')->where('key', 'maintenance_mode')->first();
                if ($setting && $setting->value == '1') {
                    $msgSetting = DB::table('settings')->where('key', 'maintenance_message')->first();
                    $message = !empty($msgSetting->value) 
                        ? $msgSetting->value 
                        : 'Sistem ToTap Store sedang dalam peningkatan performa dan pemeliharaan berkala. Kami akan segera kembali!';
                    
                    if ($request->expectsJson() || $request->is('api/*')) {
                        return response()->json([
                            'status' => 'maintenance',
                            'message' => $message,
                        ], 503);
                    }

                    return response()->view('errors.maintenance', [
                        'message' => $message
                    ], 503);
                }
            }
        } catch (\Throwable $e) {
            // Fail gracefully if database error occurs
        }

        return $next($request);
    }
}
