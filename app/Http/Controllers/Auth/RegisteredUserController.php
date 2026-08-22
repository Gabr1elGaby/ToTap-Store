<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        if (!session()->has('url.intended') && url()->previous() !== url('/register')) {
            session(['url.intended' => url()->previous()]);
        }
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $phone = $request->phone_number;
        $otp = (string) rand(100000, 999999);

        // Simpan data pendaftaran sementara di Cache (berlaku 15 menit)
        \Illuminate\Support\Facades\Cache::put('register_data_' . $phone, [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $phone,
            'password' => Hash::make($request->password),
        ], now()->addMinutes(15));

        // Simpan OTP
        \Illuminate\Support\Facades\Cache::put('otp_' . $phone, $otp, now()->addMinutes(10));

        // Panggil server WhatsApp Bot (Node.js) untuk mengirim pesan
        try {
            $waResponse = \Illuminate\Support\Facades\Http::timeout(20)->post('http://127.0.0.1:3001/send-otp', [
                'phone' => $phone,
                'otp' => $otp
            ]);
            
            if (!$waResponse->successful()) {
                $errorMsg = $waResponse->json('error') ?? 'Gagal mengirim pesan WhatsApp. Pastikan nomor HP aktif.';
                return response()->json([
                    'errors' => ['phone_number' => [$errorMsg]]
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['phone_number' => ['Gagal terhubung ke Server WhatsApp Bot. Pastikan server bot sedang berjalan.']]
            ], 422);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'requires_otp' => true,
                'phone' => $phone
            ]);
        }

        return redirect()->back()->with('status', 'Silakan cek WhatsApp untuk OTP');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string|size:6'
        ]);

        $phone = $request->phone;
        $otp = $request->otp;

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $phone);

        if (!$cachedOtp || $cachedOtp !== $otp) {
            return response()->json([
                'errors' => ['otp' => ['Kode OTP salah atau sudah kedaluwarsa.']]
            ], 422);
        }

        $userData = \Illuminate\Support\Facades\Cache::get('register_data_' . $phone);

        if (!$userData) {
            return response()->json([
                'errors' => ['otp' => ['Sesi pendaftaran habis. Silakan daftar ulang.']]
            ], 422);
        }

        // Buat user
        $user = User::create($userData);

        // Bersihkan cache
        \Illuminate\Support\Facades\Cache::forget('otp_' . $phone);
        \Illuminate\Support\Facades\Cache::forget('register_data_' . $phone);

        event(new Registered($user));

        Auth::login($user);

        return response()->json(['success' => true]);
    }
}
