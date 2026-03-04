<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function show()
    {
        return inertia('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'region' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        $validated['name'] = $validated['firstName'] . ' ' . $validated['lastName'];
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        event(new Registered($user));

        // ✅ CRITICAL FIX: Create pivot table entry immediately after user creation
        // This MUST happen BEFORE login to avoid 403 errors
        // The EnsureOrganisationMember middleware requires this pivot table entry
        try {
            // Always insert pivot entry for newly registered users
            // Do NOT check if it exists - just insert directly
            DB::table('user_organisation_roles')->insertOrIgnore([
                'user_id' => $user->id,
                'organisation_id' => $user->organisation_id ?? 1, // Fallback to platform org
                'role' => 'member',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('User registration - pivot entry created', [
                'user_id' => $user->id,
                'organisation_id' => $user->organisation_id ?? 1,
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('User registration - failed to create pivot entry', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
            // Don't fail registration - continue with login
        }

        // ✅ CRITICAL: DO NOT auto-login after registration
        // Email verification MUST happen first (Fortify requirement)
        // The Registered event will send the verification email
        // User will receive a link to verify their email

        // Redirect to verification notice page instead of auto-logging in
        // This ensures users verify their email before accessing the dashboard
        return redirect()->route('verification.notice');
    }
}
