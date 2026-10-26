<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_blocked || \App\Models\BlockedIdentity::isEmailBlocked($user->email)) {
                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Akun Anda telah dinonaktifkan/diblokir oleh Administrator.',
                    ], 403);
                }

                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan/diblokir oleh Administrator. Silakan hubungi Customer Service jika merasa ini kekeliruan.',
                ]);
            }
        }

        return $next($request);
    }
}
