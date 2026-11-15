<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The states of a scheduled resignation declaration (M-TDD-01 §5.2; D-204): scheduled → executed (its End is
 * recorded) · withdrawn (by the member, before the date started) · superseded (the period ended earlier). The three
 * end states are final. PBDIGIT-354 S2-4a.
 */
enum ScheduledResignationState: string
{
    case Scheduled = 'scheduled';
    case Executed = 'executed';
    case Withdrawn = 'withdrawn';
    case Superseded = 'superseded';
}
