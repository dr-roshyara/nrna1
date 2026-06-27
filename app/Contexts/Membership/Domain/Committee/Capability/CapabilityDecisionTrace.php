<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

final readonly class CapabilityDecisionTrace
{
    /** @param DecisionStep[] $steps */
    public function __construct(
        public string $capability,
        public bool $allowed,
        public array $steps,
        public \DateTimeImmutable $evaluatedAt,
    ) {}
}
