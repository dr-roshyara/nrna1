<?php

namespace App\Http\Controllers\Election;

use App\Contexts\Elections\Application\Commands\RecordIdentityVerificationCommand;
use App\Contexts\Elections\Application\Commands\RevokeIdentityVerificationCommand;
use App\Contexts\Elections\Application\Handlers\RecordIdentityVerificationHandler;
use App\Contexts\Elections\Application\Handlers\RevokeIdentityVerificationHandler;
use App\Contexts\Elections\Domain\Exceptions\VerifierNotAuthorised;
use App\Contexts\Elections\Domain\Exceptions\VoterNotVerifiable;
use App\Contexts\Elections\Domain\ValueObjects\AdmissionRequirement;
use App\Contexts\Elections\Domain\ValueObjects\RevocationReason;
use App\Contexts\Trust\Domain\Events\IdentityAttestedEvent;
use App\Contexts\Trust\Domain\Events\VerificationRevokedEvent;
use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\VoterVerification;
use App\Services\ElectionAuditService;
use DateTimeImmutable;
use Illuminate\Http\Request;

/**
 * VoterVerificationController — the existing "Verify" action, now recording
 * OFFICER_IDENTITY_VERIFICATION admission evidence (PBDIGIT-213 C3).
 *
 * Thin HTTP boundary: authorisation (manageSettings = Chief/Deputy of THIS election),
 * the lifecycle window (route: election.state:verify_voter_identity), then the
 * application commands. Target eligibility, the verifier's role and the evidence write
 * live in RecordIdentityVerificationHandler / RevokeIdentityVerificationHandler.
 * IP/device data is audit metadata only — never proof of identity.
 */
class VoterVerificationController extends Controller
{
    /**
     * Record identity verification (admin action, e.g. during a video call).
     */
    public function store(
        Request $request,
        Organisation $organisation,
        string $election,
        RecordIdentityVerificationHandler $handler,
    ) {
        $election = $this->resolveElection($organisation, $election);

        $this->authorize('manageSettings', $election);

        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'verified_ip' => 'nullable|ipv4',
            'verified_device_fingerprint_hash' => 'nullable|string|max:64',
            'verified_device_components' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $recorded = $handler->handle(new RecordIdentityVerificationCommand(
                electionId: $election->id,
                voterId: $validated['user_id'],
                verifierId: auth()->id(),
                verifiedIp: $validated['verified_ip'] ?? null,
                fingerprintHash: $validated['verified_device_fingerprint_hash'] ?? null,
                deviceComponents: $validated['verified_device_components'] ?? null,
                notes: $validated['notes'] ?? null,
            ));
        } catch (VoterNotVerifiable) {
            return redirect()->back()->withErrors([
                'user_id' => 'Only a registered voter of this election can be verified.',
            ]);
        } catch (VerifierNotAuthorised) {
            abort(403, 'Only the Chief or Deputy Chief of this election may verify voters.');
        }

        event(new IdentityAttestedEvent(
            verificationId: $recorded->verificationId,
            userId: $validated['user_id'],
            attestedBy: auth()->id(),
            attestedAt: new DateTimeImmutable(),
            notes: $validated['notes'] ?? null,
        ));

        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'voter_verified',
            user: auth()->user(),
            category: 'committee',
            ip: $request->ip(),
            metadata: [
                'subject_user_id' => $validated['user_id'],
                'verifier_role'   => $recorded->verifierRole->value,
                'requirement'     => AdmissionRequirement::OfficerIdentityVerification->value,
                'policy'          => $election->admissionPolicy()->toPersistence(),
                'verified_ip'     => $validated['verified_ip'] ?? null,
                'fingerprint'     => $validated['verified_device_fingerprint_hash'] ?? null,
            ]
        );

        return redirect()->back()
            ->with('success', 'Voter identity verified.');
    }

    /**
     * End the current admission effect of a voter's identity evidence.
     */
    public function revoke(
        Request $request,
        Organisation $organisation,
        string $election,
        VoterVerification $verification,
        RevokeIdentityVerificationHandler $handler,
    ) {
        $election = $this->resolveElection($organisation, $election);

        $this->authorize('manageSettings', $election);

        // Verify this verification belongs to this election
        if ($verification->election_id !== $election->id) {
            abort(404);
        }

        try {
            $handler->handle(new RevokeIdentityVerificationCommand(
                electionId: $election->id,
                voterId: $verification->user_id,
                revokedBy: auth()->id(),
                reason: RevocationReason::RevokedByOfficer,
            ));
        } catch (VerifierNotAuthorised) {
            abort(403, 'Only the Chief or Deputy Chief of this election may revoke a verification.');
        }

        event(new VerificationRevokedEvent(
            verificationId: $verification->id,
            userId: $verification->user_id,
            revokedBy: auth()->id(),
            revokedAt: new DateTimeImmutable(),
        ));

        app(ElectionAuditService::class)->log(
            election: $election,
            event: 'verification_revoked',
            user: auth()->user(),
            category: 'committee',
            ip: $request->ip(),
            metadata: [
                'subject_user_id'   => $verification->user_id,
                'revocation_reason' => RevocationReason::RevokedByOfficer->value,
            ]
        );

        return redirect()->back()
            ->with('success', 'Voter verification revoked successfully');
    }

    private function resolveElection(Organisation $organisation, string $slug): Election
    {
        return Election::withoutGlobalScopes()
            ->where('slug', $slug)
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();
    }
}
