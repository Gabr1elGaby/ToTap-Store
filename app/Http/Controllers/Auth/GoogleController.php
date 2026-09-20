<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

            // 4. Loginkan pengguna secara otomatis
            Auth::login($user, true);

            return redirect()->intended('/')->with('success', 'Selamat datang! Anda berhasil masuk dengan akun Google.');
        } catch (\Throwable $e) {
            Log::error('Google OAuth Exception: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan sistem saat mencoba masuk dengan Google.');
        }
    }
}
