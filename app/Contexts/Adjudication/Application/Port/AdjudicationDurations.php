<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Port;

use DateInterval;

/**
 * Application port for the temporal parameters the APM ENFORCES but does not OWN.
 *
 * Q-2 owns the durations; the Adjudication Process Manager is their enforcer
 * (EPIC-004K §41/§81). This port is the seam that keeps that ownership visible: the
 * manager asks for a duration and never decides one, so a constitutional value can
 * replace an interim bootstrap without touching a line of process logic.
 *
 * Resolution is per-election-type and organisation-overridable (roadmap §WP-6); both
 * arguments are optional so the ambient default is available where no scope is known.
 */
interface AdjudicationDurations
{
    /**
     * The adjudication horizon's length. Firing it yields `Expired` — never a
     * conclusion (Constitutional Policy 4).
     */
    public function maximumAdjudicationDuration(
        ?string $electionType = null,
        ?string $organisationId = null,
    ): DateInterval;
}
