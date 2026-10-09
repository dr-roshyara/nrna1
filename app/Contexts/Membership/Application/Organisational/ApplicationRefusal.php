<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/** Refusals decided by the application layer, not by the domain (Slice 1a contract §5). */
enum ApplicationRefusal: string
{
    case NotAuthorised = 'not_authorised';
    case MemberNotFound = 'member_not_found';
    /** R-1a-2: still conflicting after the bounded number of re-decisions. */
    case ConcurrentModification = 'concurrent_modification';
    /** IDEM-2: the idempotency key was already used for a different command. */
    case IdempotencyKeyConflict = 'idempotency_key_conflict';
}
