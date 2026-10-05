<?php

namespace App\Contexts\Elections\Application\Commands;

/**
 * RecordIdentityVerificationCommand — the Chief or Deputy Chief of the election
 * attests a registered voter's identity (PBDIGIT-213 C3). Pure DTO.
 *
 * The verifier's role is NOT part of the command: it is derived from the election's
 * actual leadership, never trusted from the request.
 */
final readonly class RecordIdentityVerificationCommand
{
    /** The individual attestation the officer accepts (PBDIGIT-213 C4); recorded in the audit. */
    public const ATTESTATION_STATEMENT = "I confirm that this voter's identity has been verified according to the organisation's voter-registration process.";

    public function __construct(
        public string $electionId,
        public string $voterId,
        public string $verifierId,
        public ?string $verifiedIp = null,
        public ?string $fingerprintHash = null,
        public ?array $deviceComponents = null,
        public ?string $notes = null,
    ) {}
}
