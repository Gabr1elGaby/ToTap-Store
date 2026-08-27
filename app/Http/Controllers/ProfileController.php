<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (Name & Email).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Send WhatsApp OTP for phone number change.
     */
    public function sendPhoneOtp(Request $request)
    {
        $request->validate([
            'phone_number' => [
                'required',
                'string',
                'min:9',
                'max:20',
                \Illuminate\Validation\Rule::unique('users', 'phone_number')->ignore($request->user()->id),
            ],
        ], [
            'phone_number.required' => 'Nomor WhatsApp wajib diisi.',
            'phone_number.unique' => 'Nomor WhatsApp ini sudah digunakan oleh akun lain.',
            'phone_number.min' => 'Nomor WhatsApp minimal 9 digit.',
        ]);

        $user = $request->user();

        if (!empty($user->phone_number) && $user->phone_number === $request->phone_number) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp baru sama dengan nomor WhatsApp saat ini.'
            ], 422);
        }

        $otp = (string) rand(100000, 999999);

        // Cache OTP & pending phone for 10 minutes
        \Illuminate\Support\Facades\Cache::put('change_phone_otp_' . $user->id, $otp, now()->addMinutes(10));
        \Illuminate\Support\Facades\Cache::put('change_phone_number_' . $user->id, $request->phone_number, now()->addMinutes(10));

        // Send via Fonnte WhatsApp API
        try {
            $fonnteToken = \App\Models\Setting::get('fonnte_token') ?: (config('services.fonnte.token') ?: env('FONNTE_TOKEN', '7rYeC3EsZAxizJDTFPin'));
            $userName = $user->name ?? 'Pengguna';
            $message = "*[ ToTap Store - Verifikasi Ubah Nomor WhatsApp ]*\n\nHalo {$userName},\n\nKode OTP untuk memverifikasi perubahan nomor WhatsApp akun Anda adalah:\n\n👉 *{$otp}*\n\nKode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapa pun demi keamanan akun Anda.\n\nTerima kasih,\nTim ToTap Store";

            $waResponse = \Illuminate\Support\Facades\Http::timeout(15)->withHeaders([
                'Authorization' => $fonnteToken,
            ])->post('https://api.fonnte.com/send', [
                'target' => $request->phone_number,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $resData = $waResponse->json();
            if (!$waResponse->successful() || (isset($resData['status']) && $resData['status'] === false)) {
                $errorReason = $resData['reason'] ?? ($resData['message'] ?? 'Gagal mengirim pesan WhatsApp. Pastikan nomor aktif di WhatsApp.');
                return response()->json([
                    'success' => false,
                    'message' => $errorReason
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gangguan gateway WhatsApp: ' . $e->getMessage()
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP 6-digit berhasil dikirim ke WhatsApp ' . $request->phone_number,
            'phone' => $request->phone_number
        ]);
    }

    /**
     * Verify WhatsApp OTP and update user phone number.
     */
    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size' => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        $user = $request->user();
        $cachedOtp = \Illuminate\Support\Facades\Cache::get('change_phone_otp_' . $user->id);
        $pendingPhone = \Illuminate\Support\Facades\Cache::get('change_phone_number_' . $user->id);

        if (!$cachedOtp || !$pendingPhone) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi OTP sudah kedaluwarsa atau belum diminta. Silakan klik kirim OTP ulang.'
            ], 422);
        }

        if ($cachedOtp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP yang Anda masukkan salah. Periksa kembali WhatsApp Anda.'
            ], 422);
        }

        // OTP Valid: Update User Phone Number
        $user->phone_number = $pendingPhone;
        $user->save();

        // Clear Cache
        \Illuminate\Support\Facades\Cache::forget('change_phone_otp_' . $user->id);
        \Illuminate\Support\Facades\Cache::forget('change_phone_number_' . $user->id);

        return response()->json([
            'success' => true,
            'message' => 'Nomor WhatsApp berhasil diperbarui dan diverifikasi!',
            'phone_number' => $pendingPhone
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
