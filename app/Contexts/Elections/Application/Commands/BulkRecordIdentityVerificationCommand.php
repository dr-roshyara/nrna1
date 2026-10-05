<?php

namespace App\Contexts\Elections\Application\Commands;

/**
 * BulkRecordIdentityVerificationCommand — "Verify All": the Chief or Deputy Chief of
 * the election attests the identity of every voter in the C4-D1 set at once
 * (PBDIGIT-213 C4). Pure DTO.
 *
 * expectedCount is the number the officer saw and confirmed (C4-D3); the server
 * recomputes the set and refuses a mismatch. The statement is audit data only.
 */
final readonly class BulkRecordIdentityVerificationCommand
{
    public const ATTESTATION_STATEMENT =
        "I confirm that the identities of these voters have already been verified according to the organisation's voter-registration process.";

    public function __construct(
        public string $electionId,
        public string $verifierId,
        public int $expectedCount,
        public string $statement = self::ATTESTATION_STATEMENT,
    ) {}
}
