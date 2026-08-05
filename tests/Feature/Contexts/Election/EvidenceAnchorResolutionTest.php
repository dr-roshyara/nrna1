<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Election;

use App\Contexts\Election\Application\Port\EvidenceAnchorResolver;
use App\Contexts\Election\Application\Port\EvidencePreservationDurations;
use App\Contexts\Election\Application\Service\ResolvesEvidencePreservationWindow;
use App\Contexts\Election\Infrastructure\Config\TemporaryDefaultAnchorResolver;
use App\Models\Election;
use DateInterval;
use DateTimeImmutable;
use Tests\TestCase;

/**
 * WP-7B-R1 — Interim Anchor Extraction (R-60 opened · R-70 authorized).
 *
 * The interim anchor rule was a PRIVATE METHOD inside
 * {@see ResolvesEvidencePreservationWindow}. Q-2 has not ruled which date anchors an
 * Evidence Preservation Window, so the rule is provisional by construction — and a
 * provisional rule buried in a private method can only be replaced by EDITING the
 * service. Behind a port it is replaced by SWAPPING AN IMPLEMENTATION.
 *
 * That is this slice's whole purpose, and its constraint: **no externally observable
 * behaviour changes.** The candidate order and the absent-anchor result are moved, not
 * altered. The four pre-existing suites are the harness that proves it — they must stay
 * green UNMODIFIED (R-70).
 *
 * WHAT THIS SLICE DOES NOT DO (defended absences): it does not DECIDE the anchor — that
 * is Q-2's act · it does not touch `EvidencePreservationWindow` (Domain) or
 * `EvidencePreservationDurations` · it does not move the *absent anchor ⇒ window reported
 * OPEN* rule, which is use-case policy and stays in the service (P7B-2).
 *
 * Traceability: R-60 · R-70 · Constitutional Policy 2 · AP-1 (fail closed) · Q-2 (open).
 */
final class EvidenceAnchorResolutionTest extends TestCase
{
    /** 30 + 60 + 30 = a 120-day window from whatever anchor is resolved. */
    private function durations(): EvidencePreservationDurations
    {
        return new class implements EvidencePreservationDurations {
            public function contestationWindow(?string $electionType = null, ?string $organisationId = null): DateInterval
            {
                return new DateInterval('P30D');
            }

            public function maximumAdjudicationDuration(?string $electionType = null, ?string $organisationId = null): DateInterval
            {
                return new DateInterval('P60D');
            }

            public function legalSafetyMargin(?string $electionType = null, ?string $organisationId = null): DateInterval
            {
                return new DateInterval('P30D');
            }
        };
    }

    private function election(): Election
    {
        $election = new Election();
        $election->type = 'board_election';

        return $election;
    }

    // ── R1-K1: the port exists and is bound to the interim implementation ────

    /**
     * The extraction is only real if the container resolves it. R-60's purpose —
     * *"Q-2's eventual ruling then SWAPS AN IMPLEMENTATION"* — is a statement about a
     * binding, so the binding is the keystone.
     */
    public function test_r1k1_the_anchor_resolver_port_is_bound_to_the_temporary_default_implementation(): void
    {
        $this->assertInstanceOf(
            TemporaryDefaultAnchorResolver::class,
            $this->app->make(EvidenceAnchorResolver::class),
            'Q-2 must be applicable by swapping this binding, not by editing the service',
        );
    }

    // ── R1-K2: the service resolves its anchor THROUGH the port ──────────────

    /**
     * The discriminating test. A stub resolver returns an anchor far older than the
     * election's own `end_date`; if the service still consults its former private method
     * the window is open, and if it consults the port the window is long closed.
     *
     * Without this, R1-K1 would prove only that a class exists somewhere unused.
     */
    public function test_r1k2_the_window_service_resolves_its_anchor_through_the_port(): void
    {
        $election = $this->election();
        $election->end_date = new DateTimeImmutable('2026-03-01T00:00:00+00:00');

        $this->app->instance(EvidencePreservationDurations::class, $this->durations());
        $this->app->instance(EvidenceAnchorResolver::class, new class implements EvidenceAnchorResolver {
            public function anchorFor(Election $election): ?DateTimeImmutable
            {
                return new DateTimeImmutable('2020-01-01T00:00:00+00:00');
            }
        });

        $this->assertFalse(
            $this->app->make(ResolvesEvidencePreservationWindow::class)->isOpenFor(
                $election,
                new DateTimeImmutable('2026-04-01T00:00:00+00:00'),
            ),
            'the anchor must come from the port — the stub anchor closed this window in 2020',
        );
    }

    // ── R1-K3: the interim rule is MOVED, not altered ────────────────────────

    /**
     * The behaviour-preservation keystone at the level of the rule itself: the candidate
     * order is `results_published_at` → `end_date` → `archived_at`, narrowest to
     * broadest, and it must survive the move byte-for-byte in meaning.
     */
    public function test_r1k3_the_temporary_default_resolver_preserves_the_candidate_order(): void
    {
        $resolver = new TemporaryDefaultAnchorResolver();

        $all = $this->election();
        $all->results_published_at = new DateTimeImmutable('2026-01-01T00:00:00+00:00');
        $all->end_date = new DateTimeImmutable('2026-02-01T00:00:00+00:00');
        $all->archived_at = new DateTimeImmutable('2026-03-01T00:00:00+00:00');

        $withoutFirst = $this->election();
        $withoutFirst->results_published_at = null;
        $withoutFirst->end_date = new DateTimeImmutable('2026-02-01T00:00:00+00:00');
        $withoutFirst->archived_at = new DateTimeImmutable('2026-03-01T00:00:00+00:00');

        $lastOnly = $this->election();
        $lastOnly->results_published_at = null;
        $lastOnly->end_date = null;
        $lastOnly->archived_at = new DateTimeImmutable('2026-03-01T00:00:00+00:00');

        $this->assertEquals(new DateTimeImmutable('2026-01-01T00:00:00+00:00'), $resolver->anchorFor($all));
        $this->assertEquals(new DateTimeImmutable('2026-02-01T00:00:00+00:00'), $resolver->anchorFor($withoutFirst));
        $this->assertEquals(new DateTimeImmutable('2026-03-01T00:00:00+00:00'), $resolver->anchorFor($lastOnly));
    }

    // ── R1-K4: no candidate ⇒ no anchor (the service still owns the fallback) ─

    /**
     * The resolver reports ABSENCE; it does not substitute a date. Deciding what absence
     * means is use-case policy and stays in the service (P7B-2) — which is why this
     * asserts `null` here and `EvidencePreservationWindowResolutionTest::test_k9`
     * continues to assert *reported OPEN* there, unmodified.
     */
    public function test_r1k4_the_temporary_default_resolver_reports_absence_rather_than_substituting_a_date(): void
    {
        $none = $this->election();
        $none->results_published_at = null;
        $none->end_date = null;
        $none->archived_at = null;

        $this->assertNull(
            (new TemporaryDefaultAnchorResolver())->anchorFor($none),
            'an election with no candidate yields no window rather than a substituted one',
        );
    }
}
