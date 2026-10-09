<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Why a decision was refused. Every case names one contract rule.
 */
enum RefusalReason: string
{
    /** Guard: a fact id must be unique within the Member (premise of contract Theorem 1). */
    case DuplicateFactId = 'duplicate_fact_id';

    /** Guard (contract §17): an initial term must end after the admission. */
    case InvalidInitialTerm = 'invalid_initial_term';

    /** INV-9 (BD-I5): membership periods of one Member never overlap. */
    case OverlappingMembershipPeriods = 'overlapping_membership_periods';
}
