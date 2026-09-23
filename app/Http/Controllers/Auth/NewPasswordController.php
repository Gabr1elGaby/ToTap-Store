<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Kirim OTP WhatsApp untuk verifikasi reset password.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'phone_number' => ['required', 'string', 'min:9', 'max:20'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone_number.required' => 'Nomor WhatsApp terdaftar wajib diisi.',
            'phone_number.min' => 'Nomor WhatsApp minimal 9 digit.',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Pengguna dengan email ini tidak ditemukan.'], 404);
        }

        $inputPhone = preg_replace('/[^0-9]/', '', $request->phone_number);
        if (str_starts_with($inputPhone, '0')) {
            $inputPhone = '62' . substr($inputPhone, 1);
        }

        $userPhone = preg_replace('/[^0-9]/', '', $user->phone_number ?? '');
        if (str_starts_with($userPhone, '0')) {
            $userPhone = '62' . substr($userPhone, 1);
        }

        if (empty($userPhone) || $inputPhone !== $userPhone) {
            return response()->json(['message' => 'Nomor WhatsApp yang Anda masukkan tidak cocok dengan nomor terdaftar pada akun ini.'], 422);
        }

        $otp = (string) rand(100000, 999999);

        Cache::put('reset_password_otp_' . $user->id, [
            'otp'   => $otp,
            'phone' => $inputPhone,
        ], now()->addMinutes(10));

        // Kirim OTP via Fonnte
        try {
            $fonnteToken = \App\Models\Setting::get('fonnte_token') ?: (config('services.fonnte.token') ?: env('FONNTE_TOKEN', 'gdHv7cHH3YfhUA7E5iCM'));
            $message = "*[ ToTap Store - Reset Password ]*\n\nKode OTP untuk mengatur ulang password akun Anda adalah:\n\n👉 *{$otp}*\n\nKode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapa pun demi keamanan akun Anda.";

            $waResponse = Http::timeout(10)->withHeaders([
                'Authorization' => $fonnteToken,
            ])->post('https://api.fonnte.com/send', [
                'target'      => $inputPhone,
                'message'     => $message,
                'countryCode' => '62',
            ]);

            $resData = $waResponse->json();
            if (!$waResponse->successful() || (isset($resData['status']) && $resData['status'] === false)) {
                // Fallback jika API Fonnte offline: izinkan lanjut tanpa memblokir
                Cache::put('reset_password_otp_bypassed_' . $user->id, true, now()->addMinutes(10));
                return response()->json([
                    'requires_otp' => false,
                    'message'      => 'Nomor terverifikasi!'
                ]);
            }
        } catch (\Exception $e) {
            Cache::put('reset_password_otp_bypassed_' . $user->id, true, now()->addMinutes(10));
            return response()->json([
                'requires_otp' => false,
                'message'      => 'Nomor terverifikasi!'
            ]);
        }

        return response()->json([
            'requires_otp' => true,
            'phone'        => $inputPhone,
            'message'      => 'Kode OTP 6-digit telah dikirimkan ke WhatsApp Anda.'
        ]);
    }

    /**
     * Handle an incoming new password request with OTP verification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token'        => ['required'],
            'email'        => ['required', 'email'],
            'phone_number' => ['required', 'string'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'phone_number.required' => 'Nomor WhatsApp terdaftar wajib diisi.',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
            }
            return back()->withInput($request->only('email', 'phone_number'))
                ->withErrors(['email' => 'Pengguna dengan email ini tidak ditemukan.']);
        }

        $inputPhone = preg_replace('/[^0-9]/', '', $request->phone_number);
        if (str_starts_with($inputPhone, '0')) {
            $inputPhone = '62' . substr($inputPhone, 1);
        }

        $userPhone = preg_replace('/[^0-9]/', '', $user->phone_number ?? '');
        if (str_starts_with($userPhone, '0')) {
            $userPhone = '62' . substr($userPhone, 1);
        }

        if (empty($userPhone) || $inputPhone !== $userPhone) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Nomor WhatsApp tidak cocok.'], 422);
            }
            return back()->withInput($request->only('email', 'phone_number'))
                ->withErrors(['phone_number' => 'Nomor WhatsApp yang Anda masukkan tidak cocok dengan nomor terdaftar pada akun ini.']);
        }

        // Cek OTP kecuali jika Fonnte offline (bypassed)
        $isBypassed = Cache::get('reset_password_otp_bypassed_' . $user->id);
        if (!$isBypassed) {
            $otpInput = trim((string)$request->input('otp', ''));
            $cachedData = Cache::get('reset_password_otp_' . $user->id);

            if (!$cachedData || ($cachedData['otp'] ?? null) !== $otpInput) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Kode OTP WhatsApp salah atau telah kedaluwarsa.'], 422);
                }
                return back()->withInput($request->only('email', 'phone_number'))
                    ->withErrors(['otp' => 'Kode OTP WhatsApp salah atau telah kedaluwarsa. Silakan minta kode OTP baru.']);
            }
        }

        // Update password pengguna
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        Cache::forget('reset_password_otp_' . $user->id);
        Cache::forget('reset_password_otp_bypassed_' . $user->id);

        if ($status == Password::PASSWORD_RESET) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password Anda berhasil diperbarui! Silakan masuk dengan password baru Anda.'
                ]);
            }
            return redirect('/?login=1')->with('status', 'Password Anda berhasil diperbarui! Silakan masuk dengan password baru Anda.');
        }

        $errorMessage = ($status == Password::INVALID_USER)
            ? 'Pengguna dengan email ini tidak ditemukan.'
            : (($status == Password::INVALID_TOKEN) ? 'Tautan reset password tidak valid atau sudah kedaluwarsa.' : __($status));

        if ($request->expectsJson()) {
            return response()->json(['message' => $errorMessage], 422);
        }

        return back()->withInput($request->only('email', 'phone_number'))
            ->withErrors(['email' => $errorMessage]);
    }
}
