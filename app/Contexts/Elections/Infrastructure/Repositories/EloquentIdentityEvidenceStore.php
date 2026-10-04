<?php

namespace App\Contexts\Elections\Infrastructure\Repositories;

use App\Contexts\Elections\Application\Ports\IdentityEvidenceRecord;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceStore;
use App\Contexts\Elections\Domain\ValueObjects\RevocationReason;
use App\Models\Election;
use App\Models\VoterVerification;

/**
 * EloquentIdentityEvidenceStore — voter_verifications as Admission Evidence
 * (PBDIGIT-213 C3; ADR_20261004_1549 D3, §2.2 inv. 9).
 *
 * - recordActive(): makes the (election, voter) evidence active and records who
 *   verified, in which role, by which method; clears any earlier revocation.
 *   One row per (election, voter) — a re-verification replaces the current fields
 *   (technical limitation G1; the audit trail keeps the history of acts).
 * - revokeActive(): ends the CURRENT admission effect; the row and its verified_*
 *   facts are kept.
 * Writes go through the model, whose booted() hook invalidates the cached standing.
 */
final class EloquentIdentityEvidenceStore implements IdentityEvidenceStore
{
    private const METHOD = 'officer_attestation';

    public function recordActive(IdentityEvidenceRecord $record): string
    {
        $organisationId = Election::withoutGlobalScope('tenant')->whereKey($record->electionId)->value('organisation_id');

        $evidence = VoterVerification::updateOrCreate(
            ['election_id' => $record->electionId, 'user_id' => $record->voterId],
            [
                'organisation_id'                  => $organisationId,
                'verified_ip'                      => $record->verifiedIp,
                'verified_device_fingerprint_hash' => $record->fingerprintHash,
                'verified_device_components'       => $record->deviceComponents,
                'notes'                            => $record->notes,
                'verified_by'                      => $record->verifierId,
                'verifier_role'                    => $record->verifierRole->value,
                'method'                           => self::METHOD,
                'verified_at'                      => now(),
                'status'                           => 'active',
                'revoked_by'                       => null,
                'revoked_at'                       => null,
                'revocation_reason'                => null,
            ],
        );

        return $evidence->id;
    }

    public function revokeActive(string $electionId, string $voterId, string $revokedBy, RevocationReason $reason): ?string
    {
        $evidence = VoterVerification::where('election_id', $electionId)
            ->where('user_id', $voterId)
            ->where('status', 'active')
            ->first();

        if ($evidence === null) {
            return null;
        }

        $evidence->update([
            'status'            => 'revoked',
            'revoked_by'        => $revokedBy,
            'revoked_at'        => now(),
            'revocation_reason' => $reason->value,
        ]);

        return $evidence->id;
    }
}
