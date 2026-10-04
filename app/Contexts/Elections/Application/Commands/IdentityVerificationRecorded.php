<?php

namespace App\Contexts\Elections\Application\Commands;

use App\Contexts\Elections\Domain\ValueObjects\VerifierRole;

/** Result of RecordIdentityVerificationHandler. */
final readonly class IdentityVerificationRecorded
{
    public function __construct(
        public string $verificationId,
        public VerifierRole $verifierRole,
    ) {}
}
