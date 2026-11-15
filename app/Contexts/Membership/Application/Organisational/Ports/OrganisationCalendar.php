<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * D-239 (PBDIGIT-354 Q-4, Option A): what is this organisation's local calendar date at instant t?
 *
 * - **Consumer-owned:** Membership defines it because executing a scheduled resignation needs it (M-TDD-01 §5.2: the
 *   ledger's closedThrough may not pass the start of an organisation-local date with an unexecuted due declaration).
 * - **The production answer belongs to the Organisation context (S4)**, which owns the organisation's settings. Until
 *   then there is NO production implementation, and the closing step keeps `NoDueScheduledResignations`.
 * - Never the legacy central `organisations.timezone`, never a Membership-owned time zone, never UTC as a substitute.
 * - The answer is a day ordinal (days since 1970-01-01, as every valid-time date); the domain never sees a time zone.
 * - Any failure must throw: the close then holds (MI-10).
 */
interface OrganisationCalendar
{
    public function localDateAt(TenantId $organisation, Instant $t): TimePoint;
}
