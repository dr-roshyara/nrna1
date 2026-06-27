<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Query;

/**
 * Eligible Committee Read View
 *
 * Immutable DTO representing a committee that a member is constitutionally eligible to join.
 * Decorated with current association and application state.
 *
 * No domain objects exposed (purely primitive values).
 * Designed for read-model consumption (dashboards, UI, reports).
 */
final readonly class EligibleCommitteeView
{
    public function __construct(
        public string $committeeId,
        public string $committeeName,
        public string $committeeCode,
        public int $governanceLevel,         // levelIndex from committee — 0 = central, NULL treated as INT_MAX
        public bool $hasActiveAssociation,   // member already belongs (MembershipLineage exists && isActive)
        public bool $hasPendingApplication,  // member has active MembershipApplication pending approval
    ) {}
}
