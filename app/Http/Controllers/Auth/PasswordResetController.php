<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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

        // Send the password reset link. Password::sendResetLink() dispatches
        // the notification mail synchronously — a transport-level failure
        // (e.g. the mail account's outbound sending being disabled) throws
        // instead of returning a status string, and nothing upstream catches
        // it. Left uncaught, that surfaces as a raw 500 to the user (the
        // full debug page if APP_DEBUG=true, an unstyled generic error page
        // otherwise) instead of the same graceful "couldn't send" response
        // every other failure status already gets below.
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (TransportExceptionInterface $e) {
            Log::error('❌ Password reset link mail transport failure', [
                'email' => $request->email,
                'exception' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'email' => 'We were unable to send the reset email right now. Please try again later.',
            ]);
        }

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
