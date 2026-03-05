<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        // Phase 6: Demo → Paid Flow
        // New user gets platform org membership and becomes demo user

        DB::transaction(function () use ($user) {
            // Get platform organisation
            $platformOrg = \App\Models\Organisation::getDefaultPlatform();

            if (!$platformOrg) {
                throw new \Exception('Platform organisation not found. Please ensure the platform org is created.');
            }

            // Create pivot - user is MEMBER of platform org (not owner)
            \App\Models\UserOrganisationRole::create([
                'user_id' => $user->id,
                'organisation_id' => $platformOrg->id,
                'role' => 'member',
            ]);

            // Set current org to platform so user sees demo elections
            $user->update(['organisation_id' => $platformOrg->id]);

            Log::info('User registration - platform membership created', [
                'user_id' => $user->id,
                'organisation_id' => $platformOrg->id,
                'email' => $user->email,
            ]);
        });

        // ✅ CRITICAL: DO NOT auto-login after registration
        // Email verification MUST happen first (Fortify requirement)
        // The Registered event will send the verification email
        // User will receive a link to verify their email

        // Redirect to verification notice page instead of auto-logging in
        // This ensures users verify their email before accessing the dashboard
        return redirect()->route('verification.notice');
    }
}
