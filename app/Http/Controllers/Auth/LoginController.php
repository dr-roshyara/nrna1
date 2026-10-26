<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\DashboardResolver;
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
        // ✅ FIX: If already authenticated, route to their dashboard
        // This prevents redirect loops when guest middleware is in effect
        if (auth()->check()) {
            return app(DashboardResolver::class)->resolve(auth()->user());
        }

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

        // ✅ CRITICAL: Check email verification FIRST
        // Newly registered users MUST verify email before accessing dashboard
        if ($user->email_verified_at === null) {
            return redirect()->route('verification.notice')
                ->with('status', 'Please verify your email address to continue.');
        }

        // Redirect to pending officer invitation acceptance if one is waiting
        $pending = session('pending_acceptance');
        if ($pending && isset($pending['url'])) {
            return redirect($pending['url']);
        }

        // Redirect to intended URL if set (e.g., organisation member invitation acceptance)
        if (session()->has('url.intended')) {
            return redirect()->intended();
        }

        // ✅ Use DashboardResolver for intelligent post-login routing
        // This handles:
        // - Active voting sessions
        // - Active elections
        // - Onboarding status
        // - Platform vs custom organisation routing
        return app(DashboardResolver::class)->resolve($user);
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
