<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\Port;

use DateInterval;

/**
 * Application port for the three durations Constitutional Policy 2 sums into an
 * election's Evidence Preservation Window:
 *
 *     EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin
 *
 * **Q-2 owns every one of them; Election only CONSUMES them.** This port is the seam
 * that keeps that ownership visible — the caller asks for a duration and never decides
 * one, so a constitutional value can replace an interim bootstrap without touching a
 * line of retention logic.
 *
 * WHY ELECTION DECLARES ITS OWN PORT (R-44, A-1 ratified). The plan originally read
 * *"consume Adjudication's existing `AdjudicationDurations` port"*. That sentence
 * recorded an invariant and a mechanism together, and only the invariant was binding:
 *
 *   - **Invariant (AP-2, binding):** MAD has exactly ONE canonical home,
 *     `config/adjudication.php`. Preserved absolutely — this context adds no second copy.
 *   - **Mechanism (substitutable):** importing Adjudication's interface. That is a
 *     direct cross-context code dependency, which **TP-1 forbids** and Deptrac fails.
 *
 * So the port belongs to the **consumer**, in the consumer's language — plain Hexagonal
 * practice. MAD is not Adjudication's data; it is Q-2's *policy*, and both contexts are
 * downstream of governance rather than of each other.
 *
 * Resolution is per-election-type and organisation-overridable; both arguments are
 * optional so the ambient default is available where no scope is known — the same shape
 * `AdjudicationDurations` already resolves.
 */
interface EvidencePreservationDurations
{
    /**
     * How long a legally permissible challenge may still be initiated.
     * Policy 2: *"the Contestation Window itself must be explicitly defined"*.
     */
    public function contestationWindow(
        ?string $electionType = null,
        ?string $organisationId = null,
    ): DateInterval;

    /**
     * The adjudication horizon's length — **consumed from its one home**, never redefined
     * here (AP-2). Offered on this port because Policy 2's sum needs all three terms in
     * one vocabulary, not because Election owns the value.
     */
    public function maximumAdjudicationDuration(
        ?string $electionType = null,
        ?string $organisationId = null,
    ): DateInterval;

    /**
     * The buffer beyond challenge-and-resolution, so evidence never expires exactly as
     * the last permissible action closes.
     */
    public function legalSafetyMargin(
        ?string $electionType = null,
        ?string $organisationId = null,
    ): DateInterval;
}
