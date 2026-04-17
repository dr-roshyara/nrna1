<?php

namespace App\Http\Controllers;

use App\Models\VoterInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class VoterInvitationController extends Controller
{
    public function showSetPassword(string $token)
    {
        $invitation = VoterInvitation::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->with(['user', 'organisation'])
            ->firstOrFail();

        // Load election without global scopes (it may belong to a different tenant context)
        $election = \App\Models\Election::withoutGlobalScopes()
            ->findOrFail($invitation->election_id);

        return Inertia::render('Auth/SetPassword', [
            'token' => $token,
            'email' => $invitation->user->email,
            'name' => $invitation->user->name,
            'election' => $election->name,
            'organisation' => $invitation->organisation->name,
        ]);
    }

    public function setPassword(Request $request, string $token)
    {
        $invitation = VoterInvitation::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->with(['user', 'organisation', 'election'])
            ->firstOrFail();

        $request->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ]);

        $user = $invitation->user;
        $user->update([
            'password' => bcrypt($request->password),
            'email_verified_at' => now(),
        ]);

        $invitation->update(['used_at' => now()]);

        Auth::login($user);

        $election = $invitation->election;

        return redirect()->route('elections.show', ['slug' => $election->slug]);
    }
}
