<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        try {
            // We will send the password reset link to this user.
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Password reset mail failed: ' . $e->getMessage());
            
            $errDetail = $e->getMessage();
            $errMessage = config('app.debug') 
                ? 'Gagal mengirim email: ' . $errDetail 
                : 'Gagal menghubungi server email. Silakan periksa pengaturan SMTP atau hubungi admin.';
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['email' => [$errMessage]],
                    'message' => $errMessage,
                ], 500);
            }

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => $errMessage]);
        }

        $isSuccess = ($status == Password::RESET_LINK_SENT);
        $message = $isSuccess 
            ? 'Link reset password telah berhasil dikirim ke email Anda! Silakan cek kotak masuk atau folder spam.' 
            : 'Email tersebut tidak terdaftar di sistem kami.';

        if ($request->expectsJson() || $request->ajax()) {
            if ($isSuccess) {
                return response()->json([
                    'status' => 'success',
                    'message' => $message,
                ]);
            }

            return response()->json([
                'status' => 'error',
                'errors' => ['email' => [$message]],
                'message' => $message,
            ], 422);
        }

        return $isSuccess
                    ? back()->with('status', $message)
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => $message]);
    }
}
