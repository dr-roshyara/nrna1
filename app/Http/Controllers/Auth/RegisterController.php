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

        // Get the default platform organisation
        $platformOrg = \App\Models\Organisation::getDefaultPlatform();
        if (!$platformOrg) {
            throw new \Exception('Platform organisation not found. Please ensure the platform org is created.');
        }

        $validated['name'] = $validated['firstName'] . ' ' . $validated['lastName'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['organisation_id'] = $platformOrg->id;

        $user = User::create($validated);

        event(new Registered($user));

        // Phase 6: Demo → Paid Flow
        // New user gets platform org membership and becomes demo user

        DB::transaction(function () use ($user, $platformOrg) {
            // Create pivot relationship - user is MEMBER of platform org (not owner)
            \App\Models\UserOrganisationRole::create([
                'user_id' => $user->id,
                'organisation_id' => $platformOrg->id,
                'role' => 'member',
            ]);

            Log::info('User registration - platform membership created', [
                'user_id' => $user->id,
                'organisation_id' => $platformOrg->id,
                'email' => $user->email,
            ]);
        });

        // Log the user in so they can access the verification notice page.
        // The 'verified' middleware on all protected routes will block access
        // until they confirm their email — this is safe.
        auth()->login($user);

        return redirect()->route('verification.notice');
    }
}
