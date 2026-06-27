<?php

namespace App\Domain\Election\Security\Simplified;

readonly class VerificationEvidence
{
    public function __construct(
        public bool $required,
        public bool $attested,
        public ?string $registrarId,
        public ?\DateTimeImmutable $attestationTimestamp,
    ) {}
}
