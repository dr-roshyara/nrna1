<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterIpVerification;
use App\Services\ElectionAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Capture IP Address — IP evidence, separately bounded from identity verification
 * (PBDIGIT-222; PO 2026-10-04 / 2026-10-06).
 *
 * The server records the voter's last-login IP (users.current_ip); the client sends no IP.
 * One current row per (election, voter) in voter_ip_verifications — a re-capture replaces it.
 * It never writes voter_verifications, never creates admission evidence, never raises the identity
 * event and is not read by VotingEntitlement. Display / evidence only — no enforcement (PBDIGIT-247).
 */
class VoterIpCaptureController extends Controller
{
    public function store(Request $request, Organisation $organisation, string $election): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()
            ->where('slug', $election)
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();

        $this->authorize('manageSettings', $election);   // the election's active Chief or Deputy

        // "Voter's IP Verification" setting (PO 2026-10-06): fail closed when it is off.
        if (! $election->isIpVerificationEnabled()) {
            return back()->withErrors(['ip_verification' => 'IP verification is not enabled for this election.']);
        }

        $validated = $request->validate([
            'user_id'                          => 'required|uuid|exists:users,id',
            'notes'                            => 'nullable|string|max:1000',
            // The IP is taken from the server's source, never from the client.
            'ip_address'                       => 'prohibited',
            'verified_ip'                      => 'prohibited',
            'verified_device_fingerprint_hash' => 'prohibited',
            'verified_device_components'       => 'prohibited',
        ]);

        $isRegisteredVoter = ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('user_id', $validated['user_id'])
            ->where('role', 'voter')
            ->where('status', 'active')   // a registered ACTIVE voter (PO 2026-10-06)
            ->exists();
        if (! $isRegisteredVoter) {
            return back()->withErrors(['user_id' => 'Only an active registered voter of this election can have an IP address captured.']);
        }

        $ip = User::whereKey($validated['user_id'])->value('current_ip');
        if (! is_string($ip) || filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return back()->withErrors(['user_id' => 'No valid last-login IP address is known for this voter.']);
        }

        VoterIpVerification::updateOrCreate(
            ['election_id' => $election->id, 'user_id' => $validated['user_id']],
            [
                'organisation_id' => $election->organisation_id,
                'ip_address'      => $ip,
                'captured_by'     => $request->user()->id,
                'captured_at'     => now(),
                'notes'           => $validated['notes'] ?? null,
            ],
        );

        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'voter_ip_captured',
            user: $request->user(),
            category: 'committee',
            ip: $request->ip(),
            metadata: ['subject_user_id' => $validated['user_id']],
        );

        return back()->with('success', 'Voter IP address captured.');
    }
}
