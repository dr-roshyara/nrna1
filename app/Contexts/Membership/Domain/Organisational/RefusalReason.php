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

    /** Guard (contract §18): an End targets a period of this Member. */
    case PeriodNotFound = 'period_not_found';

    /** Guard (contract §18): an End targets an open period; changing an end date is Correct(End), Slice 7. */
    case PeriodAlreadyEnded = 'period_already_ended';

    /** Guard (contract §18, C-S0-1): an End must take effect after the period start. */
    case EndNotAfterPeriodStart = 'end_not_after_period_start';

    /** INV-9 (BD-I5): membership periods of one Member never overlap. */
    case OverlappingMembershipPeriods = 'overlapping_membership_periods';

    /** INV-EP: a period's lifetime is one contiguous episode. */
    case PeriodLifetimeNotContiguous = 'period_lifetime_not_contiguous';

    /** INV-COV: consequences may move the owner of an instant, never remove membership coverage. */
    case MembershipCoverageLost = 'membership_coverage_lost';

    /** Guard (correction contract §3.1, R-2): Annul applies only to facts with decision provenance. */
    case TargetHasNoDecisionProvenance = 'target_has_no_decision_provenance';
}
