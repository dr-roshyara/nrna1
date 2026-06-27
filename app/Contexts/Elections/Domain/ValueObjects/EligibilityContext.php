<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

use App\Domain\Election\Enum\VoterSourceStrategy;

/**
 * EligibilityContext — Immutable eligibility decision input
 *
 * Represents the minimal, pure state needed for a policy to make an eligibility decision.
 * Contains NO references to Eloquent, database, or ORM.
 * Contains NO business logic — pure data carrier only.
 *
 * Used by:
 * - Policy decision tests (unit layer)
 * - Policy implementations (when called from infrastructure)
 *
 * This is a VALUE OBJECT — identity doesn't matter, only values do.
 * Business rules live in policies (Phase B), not in the context.
 */
final readonly class EligibilityContext
{
    public function __construct(
        public string $userId,
        public string $organisationId,
        public VoterSourceStrategy $mode,
        public bool $isActive = true,
        public bool $isDeleted = false,
        public ?string $membershipStatus = null,
        public ?string $feesStatus = null,
    ) {}
}
