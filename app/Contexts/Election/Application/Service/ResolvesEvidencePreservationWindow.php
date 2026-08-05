<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\Service;

use App\Contexts\Election\Application\Port\EvidenceAnchorResolver;
use App\Contexts\Election\Application\Port\EvidencePreservationDurations;
use App\Contexts\Election\Domain\EvidencePreservationWindow;
use App\Models\Election;
use DateTimeImmutable;

/**
 * Answers, for one election, whether its Evidence Preservation Window is still open.
 *
 * Election **answers**; Audit/Retention **acts** on the answer — the deletion guard is
 * not here. This service obtains the anchor and the three Policy 2 durations **through
 * ports**, invokes the window's factory and asks it. It decides nothing and validates
 * nothing.
 *
 * It owns one rule neither the window nor the anchor resolver can: **an election with no
 * anchor is reported as still open.** A window that requires an anchor can never be
 * handed one that is missing, and a resolver reports absence without interpreting it, so
 * what to do about the absence is use-case policy and lives here — fail closed, because
 * evidence must never be treated as expired on the strength of a missing fact.
 *
 * WP-7B-R1 (R-60 · R-70): the interim anchor rule moved out to
 * {@see EvidenceAnchorResolver}, **behaviour unchanged**. The open Q-2 decision now
 * replaces a binding rather than this method.
 */
final class ResolvesEvidencePreservationWindow
{
    public function __construct(
        private readonly EvidencePreservationDurations $durations,
        private readonly EvidenceAnchorResolver $anchors,
    ) {
    }

    public function isOpenFor(Election $election, DateTimeImmutable $at): bool
    {
        $anchor = $this->anchors->anchorFor($election);

        if ($anchor === null) {
            return true;
        }

        $electionType = $this->stringAttribute($election, 'type');
        $organisationId = $this->stringAttribute($election, 'organisation_id');

        return EvidencePreservationWindow::forElection(
            $anchor,
            $this->durations->contestationWindow($electionType, $organisationId),
            $this->durations->maximumAdjudicationDuration($electionType, $organisationId),
            $this->durations->legalSafetyMargin($electionType, $organisationId),
        )->isOpenAt($at);
    }

    /**
     * The legacy `Election` is an untyped Eloquent model, so every attribute arrives as
     * `mixed`. Narrow before use rather than casting blind: a non-scalar becomes `null`,
     * which the durations port already treats as "no scope — use the default".
     */
    private function stringAttribute(Election $election, string $name): ?string
    {
        $value = $election->getAttribute($name);

        return is_scalar($value) ? (string) $value : null;
    }
}
