<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Domain\GovernanceRefusal;

/** G-6c-4: a confirmation appended at a position, or refused with the domain's reasons (nothing written). */
final readonly class ConfirmationOutcome
{
    /** @param list<GovernanceRefusal> $refusals */
    private function __construct(
        public ConfirmationStatus $status,
        public ?int $position = null,
        public array $refusals = [],
    ) {
    }

    public static function confirmed(int $position): self
    {
        return new self(ConfirmationStatus::Confirmed, $position);
    }

    /** @param list<GovernanceRefusal> $refusals */
    public static function refused(array $refusals): self
    {
        return new self(ConfirmationStatus::Refused, null, $refusals);
    }
}
