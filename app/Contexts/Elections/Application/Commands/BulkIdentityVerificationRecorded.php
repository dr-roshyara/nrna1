<?php

namespace App\Contexts\Elections\Application\Commands;

use App\Contexts\Elections\Domain\ValueObjects\VerifierRole;

/**
 * Result of BulkRecordIdentityVerificationHandler (PBDIGIT-213 C4-D4): one batch id
 * correlates every per-voter audit entry and the batch summary.
 */
final readonly class BulkIdentityVerificationRecorded
{
    /** @param array<string, string> $verificationIdsByVoter voter id ⇒ evidence id */
    public function __construct(
        public string $batchId,
        public VerifierRole $verifierRole,
        public array $verificationIdsByVoter,
    ) {}

    public function count(): int
    {
        return count($this->verificationIdsByVoter);
    }
}
