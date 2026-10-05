<?php

namespace App\Contexts\Elections\Application\Ports;

use App\Contexts\Elections\Domain\ValueObjects\VerificationMethod;
use App\Contexts\Elections\Domain\ValueObjects\VerifierRole;

/**
 * IdentityEvidenceRecord — the facts of one officer identity verification, as handed
 * to IdentityEvidenceStore (ADR_20261004_1549 D3). IP/device data is audit metadata
 * only, never proof of identity (inv. 4).
 */
final readonly class IdentityEvidenceRecord
{
    public function __construct(
        public string $electionId,
        public string $voterId,
        public string $verifierId,
        public VerifierRole $verifierRole,
        public ?string $verifiedIp = null,
        public ?string $fingerprintHash = null,
        public ?array $deviceComponents = null,
        public ?string $notes = null,
        public VerificationMethod $method = VerificationMethod::OfficerAttestation,
    ) {}
}
