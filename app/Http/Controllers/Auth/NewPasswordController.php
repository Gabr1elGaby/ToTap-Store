<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'phone_number' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone_number.required' => 'Nomor WhatsApp terdaftar wajib diisi.',
        ]);

        // Verifikasi bahwa nomor HP yang dimasukkan sesuai dengan nomor HP pengguna di database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
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
            return back()->withInput($request->only('email', 'phone_number'))
                ->withErrors(['phone_number' => 'Nomor WhatsApp yang Anda masukkan tidak cocok dengan nomor terdaftar pada akun ini.']);
        }

        // Here we will attempt to reset the user's password.
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

        if ($status == Password::PASSWORD_RESET) {
            return redirect('/?login=1')->with('status', 'Password Anda berhasil diperbarui! Silakan masuk dengan password baru Anda.');
        }

        $errorMessage = ($status == Password::INVALID_USER) 
            ? 'Pengguna dengan email ini tidak ditemukan.' 
            : (($status == Password::INVALID_TOKEN) ? 'Tautan reset password tidak valid atau sudah kedaluwarsa.' : __($status));

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $errorMessage]);
    }
}
