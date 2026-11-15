<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Domain\ClaimCode;

/**
 * The result of a claim operation (I-2). `code` is present ONLY when a new claim was issued now; a retry
 * (`already-issued`) never returns a code (INV-CLM-5 analogue for ordinary claims, PI-12).
 */
final readonly class ClaimOutcome
{
    private function __construct(
        public string $status,
        public ?string $claimId,
        public ?ClaimCode $code,
        public ?string $expiresAt,
        public ?ClaimRefusal $refusal,
    ) {
    }

    public static function issued(string $claimId, ClaimCode $code, string $expiresAt): self
    {
        return new self('issued', $claimId, $code, $expiresAt, null);
    }

    public static function alreadyIssued(string $claimId): self
    {
        return new self('already-issued', $claimId, null, null, null);
    }

    public static function revoked(string $claimId): self
    {
        return new self('revoked', $claimId, null, null, null);
    }

    public static function refused(ClaimRefusal $reason): self
    {
        return new self('refused', null, null, null, $reason);
    }
}
