<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Recovery;

/**
 * The TWO governed period kinds — separate service-policy parameters that may share
 * a value but must never be forced to be the same, and are never merged
 * (EM-GOV-059(a); EM-GOV-014 Part 2):
 *  · HaltedElectionRecovery — runs only while the election is OPERATIVE and HALTED
 *  · CommitteeRestoration   — runs only while the election is INOPERATIVE
 * (EM-GOV-062 — mutually exclusive conditions, so the two can never run at once.)
 */
enum PeriodKind: string
{
    case HaltedElectionRecovery = 'halted_election_recovery';
    case CommitteeRestoration = 'committee_restoration';
}
