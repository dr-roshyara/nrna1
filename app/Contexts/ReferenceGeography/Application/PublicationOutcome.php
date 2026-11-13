<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Domain\GovernanceDecision;
use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;

final readonly class PublicationOutcome
{
    /** @param list<GovernanceRefusal> $refusals */
    private function __construct(
        public PublicationStatus $status,
        public ?int $position = null,
        public array $refusals = [],
        public ?GovernanceDecision $decision = null,
    ) {
    }

    public static function published(int $position, GovernanceDecision $decision): self
    {
        return new self(PublicationStatus::Published, $position, [], $decision);
    }

    public static function alreadyPublished(): self
    {
        return new self(PublicationStatus::AlreadyPublished);
    }

    /** @param list<GovernanceRefusal> $refusals */
    public static function refused(array $refusals, ?GovernanceDecision $decision = null): self
    {
        return new self(PublicationStatus::Refused, null, $refusals, $decision);
    }

    public static function stale(): self
    {
        return new self(PublicationStatus::Stale);
    }
}
