<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

/** Slice 1a contract §3: the outcomes of MemberRepositoryPort::append. */
enum AppendOutcome: string
{
    case Appended = 'appended';
    /** R-1a-1: the decision was based on a version that is no longer current. */
    case ConcurrencyConflict = 'concurrency_conflict';
    /** IDEM-1 / IDEM-3: the key was already applied with the same command. */
    case AlreadyApplied = 'already_applied';
    /** IDEM-2: the key was already applied with a different command. */
    case IdempotencyKeyConflict = 'idempotency_key_conflict';
    case MemberNotFound = 'member_not_found';
}
