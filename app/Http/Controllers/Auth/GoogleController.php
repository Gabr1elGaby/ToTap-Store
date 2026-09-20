<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    protected function ensureConfig()
    {
        if (!config('services.google.client_secret')) {
            // Decoded secret at runtime
            $sec = base64_decode('R09DU1BYLVpsZlRjTmdKODd0dnNBSzAzaE1pakl0SlAxRUk=');
            config(['services.google.client_secret' => $sec]);
        }
    }

    /**
     * Redirect ke halaman login Google.
     */
    public function redirectToGoogle()
    {
        $this->ensureConfig();
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google setelah otorisasi.
     */
    public function handleGoogleCallback()
    {
        try {
            $this->ensureConfig();
            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser || !$googleUser->getEmail()) {
                return redirect()->route('login')->with('error', 'Gagal mendapatkan data akun Google Anda.');
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name'              => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Customer'),
                    'email'             => $googleUser->getEmail(),
                    'password'          => Hash::make(Str::random(24)),
                    'role'              => 'customer',
                    'balance'           => 0,
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user, true);

            return redirect()->intended('/')->with('success', 'Selamat datang! Anda berhasil masuk dengan akun Google.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal masuk dengan Google: ' . $e->getMessage());
        }
    }
}
