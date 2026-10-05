<?php

namespace App\Http\Controllers\Election;

use App\Contexts\Elections\Application\Commands\BulkRecordIdentityVerificationCommand;
use App\Contexts\Elections\Application\Commands\RecordIdentityVerificationCommand;
use App\Contexts\Elections\Application\Handlers\BulkRecordIdentityVerificationHandler;
use App\Contexts\Elections\Domain\Exceptions\BulkVerificationCountChanged;
use App\Contexts\Elections\Domain\ValueObjects\VerificationMethod;
use Illuminate\Support\Facades\DB;
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
 * Identity verification carries no IP / device data (PBDIGIT-248, ID-D2): network and device
 * are the Security Policy's concern, never identity evidence.
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
            // The officer's identity attestation is required server-side — never only a UI checkbox (C4).
            'attested' => 'accepted',
            // PBDIGIT-248 (ID-D2): identity evidence carries no network / device data — that is the
            // Security Policy's concern (PBDIGIT-247 / 222). The legacy fields are refused, not stored.
            'verified_ip' => 'prohibited',
            'verified_device_fingerprint_hash' => 'prohibited',
            'verified_device_components' => 'prohibited',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $recorded = $handler->handle(new RecordIdentityVerificationCommand(
                electionId: $election->id,
                voterId: $validated['user_id'],
                verifierId: auth()->id(),
                verifiedIp: null,
                fingerprintHash: null,
                deviceComponents: null,
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
                'method'          => VerificationMethod::OfficerAttestation->value,
                'statement'       => RecordIdentityVerificationCommand::ATTESTATION_STATEMENT,
                'policy'          => $election->admissionPolicy()->toPersistence(),
            ]
        );

        return redirect()->back()
            ->with('success', 'Voter identity verified.');
    }

    /**
     * "Verify All" — one bulk attestation by the Chief or Deputy Chief of the election
     * (PBDIGIT-213 C4, PO C4-D1…D4). Same admission evidence as store(); only the
     * method differs (officer_bulk_attestation).
     *
     * Authorization has two layers: manageSettings here (outer, same rule) and the
     * handler's own VerificationAuthoritySource (authoritative). The window is the
     * route's election.state:verify_voter_identity. All evidence is written in ONE
     * transaction; a changed eligible count writes nothing. One batch id correlates
     * the per-voter audit entries and the summary (audit only).
     */
    public function verifyAll(
        Request $request,
        Organisation $organisation,
        string $election,
        BulkRecordIdentityVerificationHandler $handler,
    ) {
        $election = $this->resolveElection($organisation, $election);

        $this->authorize('manageSettings', $election);

        $validated = $request->validate([
            'attested'       => 'accepted',
            'expected_count' => 'required|integer|min:0',
        ]);

        try {
            $recorded = DB::transaction(fn () => $handler->handle(new BulkRecordIdentityVerificationCommand(
                electionId: $election->id,
                verifierId: auth()->id(),
                expectedCount: (int) $validated['expected_count'],
            )));
        } catch (BulkVerificationCountChanged $changed) {
            return redirect()->back()->withErrors([
                'expected_count' => "The number of voters to verify has changed (now {$changed->actual}). Please review and confirm again.",
            ]);
        } catch (VerifierNotAuthorised) {
            abort(403, 'Only the Chief or Deputy Chief of this election may verify voters.');
        }

        $audit = app(ElectionAuditService::class);
        foreach ($recorded->verificationIdsByVoter as $voterId => $verificationId) {
            event(new IdentityAttestedEvent(
                verificationId: $verificationId,
                userId: $voterId,
                attestedBy: auth()->id(),
                attestedAt: new DateTimeImmutable(),
                notes: BulkRecordIdentityVerificationCommand::ATTESTATION_STATEMENT,
            ));

            $audit->log(
                election: $election,
                event: 'voter_verified',
                user: auth()->user(),
                category: 'committee',
                ip: $request->ip(),
                metadata: [
                    'subject_user_id' => $voterId,
                    'verifier_role'   => $recorded->verifierRole->value,
                    'requirement'     => AdmissionRequirement::OfficerIdentityVerification->value,
                    'method'          => VerificationMethod::OfficerBulkAttestation->value,
                    'batch_id'        => $recorded->batchId,
                ]
            );
        }

        $audit->log(
            election: $election,
            event: 'voters_bulk_verified',
            user: auth()->user(),
            category: 'committee',
            ip: $request->ip(),
            metadata: [
                'batch_id'      => $recorded->batchId,
                'count'         => $recorded->count(),
                'verifier_role' => $recorded->verifierRole->value,
                'statement'     => BulkRecordIdentityVerificationCommand::ATTESTATION_STATEMENT,
                'policy'        => $election->admissionPolicy()->toPersistence(),
            ]
        );

        return redirect()->back()
            ->with('success', "{$recorded->count()} voters verified.");
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
