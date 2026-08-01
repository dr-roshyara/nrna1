<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\Service;

use App\Contexts\Election\Application\Port\EvidencePreservationDurations;
use App\Contexts\Election\Domain\EvidencePreservationWindow;
use App\Models\Election;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Answers, for one election, whether its Evidence Preservation Window is still open.
 *
 * Election **answers**; Audit/Retention **acts** on the answer — the deletion guard is
 * not here. This service resolves the anchor and the three Policy 2 durations, invokes
 * the window's factory and asks it. It decides nothing and validates nothing.
 *
 * It owns one rule the window cannot: **an election with no anchor is reported as still
 * open.** A window that requires an anchor can never be handed one that is missing, so
 * what to do about the absence is use-case policy and lives here — fail closed, because
 * evidence must never be treated as expired on the strength of a missing fact.
 */
final class ResolvesEvidencePreservationWindow
{
    public function __construct(
        private readonly EvidencePreservationDurations $durations,
    ) {
    }

    public function isOpenFor(Election $election, DateTimeImmutable $at): bool
    {
        $anchor = $this->anchorOf($election);

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
     * ⚠️ INTERIM. Policy 2 defines the window's three durations but not its START, and
     * the anchor is an open Q-2 decision. Until it is made, the first available
     * candidate is used, ordered narrowest-to-broadest; when Q-2 rules, this collapses
     * to one named field and nothing else changes.
     *
     * This resolves WHICH DATE to measure from. It invents no duration — the three
     * business values still arrive through the port, and an election with no candidate
     * at all yields no window rather than a substituted one.
     */
    private function anchorOf(Election $election): ?DateTimeImmutable
    {
        foreach (['results_published_at', 'end_date', 'archived_at'] as $candidate) {
            $value = $election->getAttribute($candidate);

            if ($value instanceof DateTimeInterface) {
                return DateTimeImmutable::createFromInterface($value);
            }
        }

        return null;
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
