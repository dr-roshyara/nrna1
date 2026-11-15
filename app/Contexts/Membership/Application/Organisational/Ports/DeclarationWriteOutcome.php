<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

/** The outcomes of writing a scheduled resignation declaration (PBDIGIT-354 S2-4b; ScheduledResignationStorePort). */
enum DeclarationWriteOutcome: string
{
    case Written = 'written';
    /** IDEM-1 / IDEM-3: the key was already applied with the same command; nothing was written again. */
    case AlreadyApplied = 'already_applied';
    /** IDEM-2: the key was already applied with a different command. */
    case IdempotencyKeyConflict = 'idempotency_key_conflict';
    /**
     * Something changed since the decision: the membership's version moved, another declaration became pending for
     * the period, the id was taken, or the declaration is no longer `scheduled` (compare-and-set, MI-2). Nothing was
     * written. Retryable: decide again from a fresh read (R-1a-2).
     */
    case Conflict = 'conflict';
    case MemberNotFound = 'member_not_found';
}
