<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailMail;
use App\Services\DashboardResolver;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerificationController extends Controller
{
    /**
     * Show the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        return inertia('Auth/VerifyEmail', [
            'status' => session('status'),
        ]);
    }

    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return app(DashboardResolver::class)->resolve($request->user());
        }

        // Generate verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $request->user()->getKey(),
                'hash' => sha1($request->user()->getEmailForVerification()),
            ]
        );

        // Send custom branded email
        Mail::send(new VerifyEmailMail($request->user(), $verificationUrl));

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return app(DashboardResolver::class)->resolve($request->user());
        }

        // Validate the hash matches the user's email
        $hash = sha1($request->user()->getEmailForVerification());
        if ($request->hash !== $hash) {
            return redirect()->route('verification.notice')
                ->with('error', 'Invalid verification link');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return app(DashboardResolver::class)->resolve($request->user())->with('status', 'email-verified');
    }
}
