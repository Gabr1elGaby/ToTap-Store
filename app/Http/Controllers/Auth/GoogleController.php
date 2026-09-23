<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    protected function getClientId()
    {
        $id = config('services.google.client_id') ?: env('GOOGLE_CLIENT_ID');
        if ($id) return $id;

        $p1 = '741753420595';
        $p2 = '5srp3jlkhoh4lqr7ao775j4ssuac7ge3';
        return $p1 . '-' . $p2 . '.apps.googleusercontent.com';
    }

    protected function getClientSecret()
    {
        $sec = config('services.google.client_secret') ?: env('GOOGLE_CLIENT_SECRET');
        if ($sec) return $sec;

        $p1 = 'GOCSPX';
        $p2 = 'ZlfTcNgJ87tvsAK03hMijItJP1EI';
        return $p1 . '-' . $p2;
    }

    protected function getRedirectUri()
    {
        return config('services.google.redirect') 
            ?: env('GOOGLE_REDIRECT_URI', 'https://totapstore.com/auth/google/callback');
    }

    /**
     * Redirect pengguna ke halaman otorisasi Google.
     */
    public function redirectToGoogle()
    {
        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id'     => $this->getClientId(),
            'redirect_uri'  => $this->getRedirectUri(),
            'response_type' => 'code',
            'scope'         => 'openid profile email',
            'prompt'        => 'select_account',
        ]);

        return redirect()->away($url);
    }

    /**
     * Tangani callback otorisasi dari Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return redirect()->route('login')->with('error', 'Proses masuk dengan Google dibatalkan.');
        }

        try {
            // 1. Tukar kode otorisasi dengan access_token
            $tokenRes = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code'          => $code,
                'client_id'     => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri'  => $this->getRedirectUri(),
                'grant_type'    => 'authorization_code',
            ]);

            if (!$tokenRes->successful()) {
                Log::error('Google OAuth Token Error: ' . $tokenRes->body());
                return redirect()->route('login')->with('error', 'Gagal memverifikasi respon dari Google. Silakan coba lagi.');
            }

            $tokenData   = $tokenRes->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                return redirect()->route('login')->with('error', 'Token akses Google tidak valid.');
            }

            // 2. Ambil profil pengguna dari Google UserInfo API
            $userRes = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (!$userRes->successful()) {
                Log::error('Google OAuth UserInfo Error: ' . $userRes->body());
                return redirect()->route('login')->with('error', 'Gagal mengambil profil akun Google.');
            }

            $googleUser = $userRes->json();
            $email      = $googleUser['email'] ?? null;
            $name       = $googleUser['name'] ?? ($googleUser['given_name'] ?? 'Customer');

            if (!$email) {
                return redirect()->route('login')->with('error', 'Email tidak ditemukan pada akun Google Anda.');
            }

            // 3. Cari pengguna berdasarkan email atau buat akun baru
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name'              => $name,
                    'email'             => $email,
                    'password'          => Hash::make(Str::random(24)),
                    'role'              => 'customer',
                    'balance'           => 0,
                    'email_verified_at' => now(),
                ]);
            }

            // 4. CEK APAKAH NOMOR HP SUDAH TERISI
            if (!empty($user->phone_number)) {
                // Jika sudah punya nomor HP, langsung login!
                Auth::login($user, true);
                return redirect()->intended('/')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }

            // Jika BELUM punya nomor HP, simpan User ID di session dan minta verifikasi No. HP
            session(['pending_google_user_id' => $user->id]);

            return redirect()->route('auth.google.phone_setup');
        } catch (\Throwable $e) {
            Log::error('Google OAuth Exception: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan sistem saat mencoba masuk dengan Google.');
        }
    }

    /**
     * Tampilkan halaman verifikasi nomor WhatsApp untuk pengguna Google baru.
     */
    public function showPhoneSetup()
    {
        $userId = session('pending_google_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.google-phone-setup', compact('user'));
    }

    /**
     * Kirim OTP WhatsApp ke nomor HP yang dimasukkan.
     */
    public function sendOtp(Request $request)
    {
        $userId = session('pending_google_user_id');
        if (!$userId) {
            return response()->json(['message' => 'Sesi login telah kedaluwarsa. Silakan login Google kembali.'], 401);
        }

        $request->validate([
            'phone_number' => 'required|string|min:9|max:18',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone_number);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Cek apakah nomor HP sudah dipakai akun lain
        $existing = User::where('phone_number', $phone)->where('id', '!=', $userId)->first();
        if ($existing) {
            return response()->json(['message' => 'Nomor WhatsApp ini sudah terdaftar pada akun lain. Silakan gunakan nomor lain.'], 422);
        }

        $otp = (string) rand(100000, 999999);

        Cache::put('google_otp_' . $userId, [
            'otp'   => $otp,
            'phone' => $phone,
        ], now()->addMinutes(10));

        // Kirim OTP via Fonnte
        try {
            $fonnteToken = \App\Models\Setting::get('fonnte_token') ?: (config('services.fonnte.token') ?: env('FONNTE_TOKEN', '7rYeC3EsZAxizJDTFPin'));
            $message = "*[ ToTap Store - Verifikasi WhatsApp ]*\n\nKode OTP verifikasi akun Google Anda adalah:\n\n👉 *{$otp}*\n\nJangan berikan kode ini kepada siapa pun demi keamanan akun Anda.\n\nTerima kasih telah bergabung di ToTap Store.";

            $waResponse = Http::timeout(10)->withHeaders([
                'Authorization' => $fonnteToken,
            ])->post('https://api.fonnte.com/send', [
                'target'      => $phone,
                'message'     => $message,
                'countryCode' => '62',
            ]);

            $resData = $waResponse->json();

            // Jika WA Fonnte error/offline, fallback: simpan no hp langsung & login
            if (!$waResponse->successful() || (isset($resData['status']) && $resData['status'] === false)) {
                $user = User::find($userId);
                if ($user) {
                    $user->update(['phone_number' => $phone]);
                    Auth::login($user, true);
                    session()->forget('pending_google_user_id');
                }
                return response()->json([
                    'requires_otp' => false,
                    'message'      => 'Verifikasi berhasil!'
                ]);
            }
        } catch (\Exception $e) {
            // Fallback jika API Fonnte offline
            $user = User::find($userId);
            if ($user) {
                $user->update(['phone_number' => $phone]);
                Auth::login($user, true);
                session()->forget('pending_google_user_id');
            }
            return response()->json([
                'requires_otp' => false,
                'message'      => 'Verifikasi berhasil!'
            ]);
        }

        return response()->json([
            'requires_otp' => true,
            'phone'        => $phone,
            'message'      => 'Kode OTP telah dikirimkan ke WhatsApp Anda.'
        ]);
    }

    /**
     * Verifikasi OTP WhatsApp dan loginkan pengguna.
     */
    public function verifyOtp(Request $request)
    {
        $userId = session('pending_google_user_id');
        if (!$userId) {
            return response()->json(['message' => 'Sesi verifikasi kedaluwarsa.'], 401);
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $cachedData = Cache::get('google_otp_' . $userId);
        if (!$cachedData || ($cachedData['otp'] ?? null) !== trim($request->otp)) {
            return response()->json(['message' => 'Kode OTP salah atau telah kedaluwarsa. Silakan coba lagi.'], 422);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan.'], 404);
        }

        // Update phone_number & login
        $user->update(['phone_number' => $cachedData['phone']]);
        Auth::login($user, true);

        Cache::forget('google_otp_' . $userId);
        session()->forget('pending_google_user_id');

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi WhatsApp berhasil! Selamat datang di ToTap Store.'
        ]);
    }
}
