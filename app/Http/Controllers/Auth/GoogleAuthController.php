<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user ke Google OAuth.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google OAuth.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }

        if (!$googleUser || !$googleUser->getEmail()) {
            return redirect()->route('login')->with('error', 'Email tidak ditemukan dari akun Google Anda.');
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id jika belum ada
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            // Buat user baru jika belum terdaftar
            $user = User::create([
                'name'      => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Pengguna Google',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'role'      => 'user',
                'password'  => Hash::make(Str::random(24)),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended('/')->with('success', 'Berhasil masuk dengan akun Google!');
    }
}
