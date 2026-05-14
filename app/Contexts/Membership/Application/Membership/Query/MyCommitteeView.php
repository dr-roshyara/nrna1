<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Query;

use DateTimeImmutable;

/**
 * MyCommitteeView
 *
 * Enhanced DTO combining Phase C eligibility with membership state.
 * Used for "My Committees" page: eligible + current memberships + pending applications.
 *
 * Primitives only (no domain objects). Immutable.
 */
final readonly class MyCommitteeView
{
    public function __construct(
        public string $committeeId,
        public string $committeeName,
        public string $committeeCode,
        public int $governanceLevel,                    // 0=central, higher=local
        public bool $hasActiveAssociation,              // Member already in committee
        public bool $hasPendingApplication,             // Member has submitted application
        public bool $canApply,                          // Can submit new application
        public ?string $applicationStatus,              // 'pending' | 'accepted' | 'rejected' | null
        public ?DateTimeImmutable $joinedDate,          // When member joined (if active)
        public ?string $roleInCommittee,                // Member's role (if active)
    ) {}
}
