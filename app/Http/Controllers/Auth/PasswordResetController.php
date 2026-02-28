<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PasswordResetController extends Controller
{
    /**
     * Display the password reset link request view
     */
    public function showLinkRequestForm()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Send a password reset link to the user
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        Log::info('📧 Password reset link requested', [
            'email' => $request->email,
        ]);

        // Send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Log::info('✅ Password reset link sent successfully', [
                'email' => $request->email,
                'status' => $status,
            ]);

            return back()->with('status', trans($status));
        }

        Log::error('❌ Failed to send password reset link', [
            'email' => $request->email,
            'status' => $status,
        ]);

        return back()->withErrors(['email' => trans($status)]);
    }

    /**
     * Display the password reset form
     */
    public function showResetForm(Request $request, $token)
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Reset the given user's password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        Log::info('🔐 Password reset attempt', [
            'email' => $request->email,
        ]);

        // Reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->saveQuietly();

                Log::info('✅ Password reset successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', trans($status));
        }

        Log::error('❌ Password reset failed', [
            'email' => $request->email,
            'status' => $status,
        ]);

        return back()->withErrors(['email' => trans($status)]);
    }
}
