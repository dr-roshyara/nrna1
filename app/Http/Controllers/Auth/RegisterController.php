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

        // ✅ CRITICAL FIX: Create pivot table entry SYNCHRONOUSLY after user creation
        // This MUST happen BEFORE redirect to avoid 403 errors
        // The EnsureOrganisationMember middleware requires this pivot table entry
        // Do NOT rely on event listener - it fails silently when organisation_id is null

        // Resolve organisation_id with fallback
        $orgId = $user->organisation_id
            ?? DB::table('organisations')->where('slug', 'publicdigit')->value('id')
            ?? 1;

        // Check if pivot already exists (from User::created() fallback)
        $pivotExists = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $orgId)
            ->exists();

        // Create pivot entry using insertOrIgnore (safe if User::created() already created it)
        // The User model has a fallback that creates pivot on created event
        // This handles both: RegisterController creates first, fallback creates if needed
        DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => $orgId,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('User registration - pivot entry ensured', [
            'user_id' => $user->id,
            'organisation_id' => $orgId,
            'email' => $user->email,
            'pivot_already_existed' => $pivotExists,
        ]);

        // ✅ CRITICAL: DO NOT auto-login after registration
        // Email verification MUST happen first (Fortify requirement)
        // The Registered event will send the verification email
        // User will receive a link to verify their email

        // Redirect to verification notice page instead of auto-logging in
        // This ensures users verify their email before accessing the dashboard
        return redirect()->route('verification.notice');
    }
}
