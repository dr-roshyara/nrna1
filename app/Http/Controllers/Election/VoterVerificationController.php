<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\VoterVerification;
use App\Services\ElectionAuditService;
use Illuminate\Http\Request;

class VoterVerificationController extends Controller
{
    /**
     * Save or update voter verification (admin action during video call)
     */
    public function store(Request $request, Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()
            ->where('slug', $election)
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();

        $this->authorize('manageSettings', $election);

        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'verified_ip' => 'nullable|ipv4',
            'verified_device_fingerprint_hash' => 'nullable|string|max:64',
            'verified_device_components' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Find or create verification record
        $verification = VoterVerification::query()
            ->where('election_id', $election->id)
            ->where('user_id', $validated['user_id'])
            ->first();

        if ($verification) {
            // Update existing record (re-verification)
            $verification->update(array_merge(
                $validated,
                [
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'status' => 'active',
                    'revoked_by' => null,
                    'revoked_at' => null,
                ]
            ));
        } else {
            // Create new verification record
            VoterVerification::create(array_merge(
                $validated,
                [
                    'election_id' => $election->id,
                    'organisation_id' => $election->organisation_id,
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'status' => 'active',
                ]
            ));
        }

        // Log voter_verified event
        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'voter_verified',
            user: auth()->user(),
            category: 'committee',
            ip: $request->ip(),
            metadata: [
                'verified_ip' => $validated['verified_ip'] ?? null,
                'fingerprint' => $validated['verified_device_fingerprint_hash'] ?? null,
            ]
        );

        return redirect()->back()
            ->with('success', 'Voter verification saved successfully');
    }

    /**
     * Revoke a voter's verification
     */
    public function revoke(Request $request, Organisation $organisation, string $election, VoterVerification $verification)
    {
        $election = Election::withoutGlobalScopes()
            ->where('slug', $election)
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();

        $this->authorize('manageSettings', $election);

        // Verify this verification belongs to this election
        if ($verification->election_id !== $election->id) {
            abort(404);
        }

        $verification->update([
            'status' => 'revoked',
            'revoked_by' => auth()->id(),
            'revoked_at' => now(),
        ]);

        // Log verification_revoked event
        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'verification_revoked',
            user: auth()->user(),
            category: 'committee',
            ip: $request->ip()
        );

        return redirect()->back()
            ->with('success', 'Voter verification revoked successfully');
    }
}
