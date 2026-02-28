<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Inertia\Inertia;

class LoginController extends Controller
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;

    public function show()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => \Route::has('password.request'),
            'canRegister' => \Route::has('register'),
            'status' => session('status'),
        ]);
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited($request);

        // Check if email exists in database
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => 'auth.email_not_registered',
            ]);
        }

        // Attempt authentication
        if (!Auth::attempt($validated, $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'password' => 'auth.failed',
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        // Get authenticated user
        $user = Auth::user();

        // If user has an organization, redirect to it; otherwise go to dashboard
        if ($user->organisation_id) {
            $organization = \App\Models\Organization::find($user->organisation_id);
            if ($organization) {
                return redirect()->intended(route('organizations.show', $organization->slug));
            }
        }

        // Fallback to dashboard if no organization
        return redirect()->intended(route('electiondashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Ensure the request is not rate limited.
     */
    protected function ensureIsNotRateLimited($request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {
            return;
        }

        event(new Lockout($request));

        throw ValidationException::withMessages([
            'email' => 'auth.throttle',
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey($request)
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
