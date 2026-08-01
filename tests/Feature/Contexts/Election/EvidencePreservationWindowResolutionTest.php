<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Election;

use App\Contexts\Election\Application\Port\EvidencePreservationDurations;
use App\Contexts\Election\Application\Service\ResolvesEvidencePreservationWindow;
use App\Models\Election;
use DateInterval;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * WP-7 Slice 7B — the application-layer collaborator that 7B necessarily contains.
 *
 * WHY THIS EXISTS IN 7B AT ALL (P7B-3, withdrawn): 7B's objective requires MAD to be
 * obtained **through Election's own port**, and the construction commission forbids the
 * Value Object a port — *"never a port, config, model or clock"*. **A pure VO therefore
 * cannot satisfy 7B's own objective**, so 7B contains a collaborator that resolves the
 * anchor and the three durations and invokes the factory. Plan §2 names it directly:
 * application orchestration *"compute an election's EPW"*.
 *
 * WHAT IT OWNS (P7B-2): the **absent-anchor fallback**. A VO that requires an anchor can
 * never be handed one that is missing — *absent anchor* is not an invalid state OF the
 * window, it is the absence of the window. Deciding what to do when a fact is missing is
 * **use-case policy**, so it lives here.
 *
 * NOT IN SCOPE: acting on the answer. Election **ANSWERS**; Audit/Retention **ACTS**
 * (R-45). The deletion guard is 7C and is not authorized.
 *
 * ⚠️ The EPW **anchor** — which date anchors the window — is an OPEN Q-2 business
 * decision. These keystones are written so they **do not depend on which candidate is
 * chosen**: K9 exercises the case where no candidate is present at all.
 *
 * Traceability: Policy 2 · R-44 (Election's own port) · R-45 (answers vs acts) ·
 * R-57 (plan mechanism corrected) · R-58 (execution authorized) · P7B-2 · P7B-3.
 */
final class EvidencePreservationWindowResolutionTest extends TestCase
{
    /** Records whether MAD was resolved through ELECTION's port. */
    private function spyDurations(): EvidencePreservationDurations
    {
        return new class implements EvidencePreservationDurations {
            public bool $madAsked = false;

            public function contestationWindow(?string $electionType = null, ?string $organisationId = null): DateInterval
            {
                return new DateInterval('P30D');
            }

            public function maximumAdjudicationDuration(?string $electionType = null, ?string $organisationId = null): DateInterval
            {
                $this->madAsked = true;

                return new DateInterval('P60D');
            }

            public function legalSafetyMargin(?string $electionType = null, ?string $organisationId = null): DateInterval
            {
                return new DateInterval('P30D');
            }
        };
    }

    private function service(EvidencePreservationDurations $durations): ResolvesEvidencePreservationWindow
    {
        $this->app->instance(EvidencePreservationDurations::class, $durations);

        return $this->app->make(ResolvesEvidencePreservationWindow::class);
    }

    /** An election with an anchor available. */
    private function anchoredElection(): Election
    {
        $election = new Election();
        $election->type = 'board_election';
        $election->end_date = new DateTimeImmutable('2026-03-01T00:00:00+00:00');

        return $election;
    }

    /** An election with NO anchor candidate populated at all. */
    private function unanchoredElection(): Election
    {
        $election = new Election();
        $election->type = 'board_election';
        $election->end_date = null;
        $election->results_published_at = null;
        $election->archived_at = null;

        return $election;
    }

    // ── K7: MAD is obtained through ELECTION's own port (R-44 · AP-2) ───────

    /**
     * The mechanism R-57 restored to the plan. MAD keeps exactly one canonical home, and
     * Election reaches it through **its own** port — importing Adjudication's is the TP-1
     * violation R-44 rejected. *(Structural enforcement is Deptrac's; this asserts the
     * behaviour: the resolution actually goes through Election's port.)*
     */
    public function test_k7_the_maximum_adjudication_duration_is_resolved_through_elections_own_port(): void
    {
        $durations = $this->spyDurations();

        $this->service($durations)->isOpenFor(
            $this->anchoredElection(),
            new DateTimeImmutable('2026-04-01T00:00:00+00:00'),
        );

        $this->assertTrue($durations->madAsked, 'MAD must be resolved through Election\'s own port');
    }

    // ── K9: absent anchor ⇒ the window is treated as OPEN (fail closed) ─────

    /**
     * P7B-2's allocation, executed. The service — not the Value Object — meets the
     * missing anchor, and **fails closed**: with no anchor there is no window to compute,
     * and evidence must not be treated as expired on the strength of a missing fact.
     *
     * This deliberately does not assert WHICH field anchors the window: the Q-2 decision
     * is open, and no keystone should quietly settle a business value.
     */
    public function test_k9_an_election_with_no_anchor_is_reported_as_still_open(): void
    {
        $this->assertTrue(
            $this->service($this->spyDurations())->isOpenFor(
                $this->unanchoredElection(),
                new DateTimeImmutable('2099-01-01T00:00:00+00:00'),
            ),
            'with no anchor the window must be treated as OPEN — never expired on a missing fact',
        );
    }
}
