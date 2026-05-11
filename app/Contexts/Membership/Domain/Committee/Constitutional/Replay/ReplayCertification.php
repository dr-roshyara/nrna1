<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Replay;

final readonly class ReplayCertification
{
    public function __construct(
        public string $decisionId,
        public bool $isEquivalent,
        public array $equivalenceBreaches,
        public string $certificationReason,
        public \DateTimeImmutable $certifiedAt,
    ) {}
}
