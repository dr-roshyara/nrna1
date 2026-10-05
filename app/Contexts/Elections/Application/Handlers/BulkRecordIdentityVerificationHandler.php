<?php

namespace App\Contexts\Elections\Application\Handlers;

use App\Contexts\Elections\Application\Commands\BulkIdentityVerificationRecorded;
use App\Contexts\Elections\Application\Commands\BulkRecordIdentityVerificationCommand;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceRecord;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceStore;
use App\Contexts\Elections\Application\Ports\VerificationAuthoritySource;
use App\Contexts\Elections\Application\Ports\VoterRegistrationQuery;
use App\Contexts\Elections\Domain\Exceptions\BulkVerificationCountChanged;
use App\Contexts\Elections\Domain\Exceptions\VerifierNotAuthorised;
use App\Contexts\Elections\Domain\Policies\VerificationAuthority;
use App\Contexts\Elections\Domain\ValueObjects\VerificationMethod;

/**
 * BulkRecordIdentityVerificationHandler — "Verify All" (PBDIGIT-213 C4, PO C4-D1…D4).
 *
 * The SAME admission evidence as the individual action (OFFICER_IDENTITY_VERIFICATION),
 * produced as one bulk attestation — not a second admission mechanism:
 *  1. authority: the Chief or Deputy Chief of THIS election, derived through
 *     VerificationAuthoritySource (independent of any route-level check);
 *  2. candidates: computed on the server (VoterRegistrationQuery::bulkVerifiableVoterIds);
 *  3. count guard: a mismatch with what the officer confirmed ⇒ refused, nothing written;
 *  4. each candidate recorded with method officer_bulk_attestation and the attestation
 *     statement as audit notes (notes never influence entitlement);
 *  5. one batch id correlates the per-voter audit entries and the summary (audit only).
 *
 * Atomicity is provided by the caller's transaction boundary (HTTP layer), so the
 * application layer stays framework-free.
 */
final class BulkRecordIdentityVerificationHandler
{
    public function __construct(
        private readonly VoterRegistrationQuery $registry,
        private readonly VerificationAuthoritySource $leadership,
        private readonly VerificationAuthority $authority,
        private readonly IdentityEvidenceStore $evidence,
    ) {}

    public function handle(BulkRecordIdentityVerificationCommand $command): BulkIdentityVerificationRecorded
    {
        $role = $this->authority->roleFor(
            $this->leadership->leadershipRoleOf($command->verifierId, $command->electionId)
        ) ?? throw VerifierNotAuthorised::forElection($command->verifierId, $command->electionId);

        $candidates = $this->registry->bulkVerifiableVoterIds($command->electionId);

        if (count($candidates) !== $command->expectedCount) {
            throw BulkVerificationCountChanged::between($command->expectedCount, count($candidates));
        }

        $recorded = [];
        foreach ($candidates as $voterId) {
            $recorded[$voterId] = $this->evidence->recordActive(new IdentityEvidenceRecord(
                electionId: $command->electionId,
                voterId: $voterId,
                verifierId: $command->verifierId,
                verifierRole: $role,
                notes: $command->statement,
                method: VerificationMethod::OfficerBulkAttestation,
            ));
        }

        return new BulkIdentityVerificationRecorded(self::newBatchId(), $role, $recorded);
    }

    /** RFC 4122 v4 UUID from random bytes (pure PHP; an audit correlation id). */
    private static function newBatchId(): string
    {
        $bytes    = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
