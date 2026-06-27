<?php

namespace App\Domain\Election\Security\Simplified;

use App\Domain\Election\Security\TrustLevel;

readonly class ElectionSecurityEvent
{
    public function __construct(
        public string $eventType,
        public int $electionId,
        public ?string $voterSlugId,
        public array $auditContext,
        public TrustLevel $trustLevelBefore,
        public TrustLevel $trustLevelAfter,
        public array $policySequence,
        public ?string $overlaySignalType,
        public string $constitutionalOutcome,
        public \DateTimeImmutable $recordedAt,
    ) {}
}
