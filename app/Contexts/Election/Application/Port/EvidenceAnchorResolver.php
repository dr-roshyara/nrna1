<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\Port;

use App\Models\Election;
use DateTimeImmutable;

/**
 * Application port for the one input Constitutional Policy 2 does NOT supply: the date an
 * election's Evidence Preservation Window is measured FROM.
 *
 *     EPW = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin
 *
 * Policy 2 fixes those three durations; **it does not fix the anchor**, and which date
 * anchors the window is an **open Q-2 decision**. This port exists so that the interim
 * answer is a *named, replaceable thing* rather than a private method: when Q-2 rules,
 * the ruling **swaps an implementation** instead of **editing service behaviour**
 * (WP-7B-R1 · R-60 · R-70).
 *
 * The seam is deliberately the same shape as {@see EvidencePreservationDurations}: the
 * caller asks, and never decides. Election consumes a policy it does not own.
 *
 * **Absence is an answer, not a failure.** An election with no candidate date yields
 * `null` — the resolver reports what it found and substitutes nothing. What absence
 * *means* is use-case policy and belongs to the consuming service (P7B-2), which fails
 * closed: no anchor ⇒ the window is reported still OPEN, because evidence must never be
 * treated as expired on the strength of a missing fact (AP-1).
 */
interface EvidenceAnchorResolver
{
    /** The date this election's Evidence Preservation Window is measured from, or `null` if none is available. */
    public function anchorFor(Election $election): ?DateTimeImmutable;
}
