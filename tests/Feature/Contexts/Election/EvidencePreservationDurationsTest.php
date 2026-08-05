<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Election;

use App\Contexts\Adjudication\Application\Port\AdjudicationDurations;
use App\Contexts\Election\Application\Port\EvidencePreservationDurations;
use Illuminate\Contracts\Config\Repository as Config;
use RuntimeException;
use Tests\TestCase;

/**
 * WP-7 Slice 7A — the retention DURATIONS (config + port + adapter).
 *
 * Scope, per the approved plan (R-46) and the authorization (R-47): **7A only**.
 * The `EvidencePreservationWindow` Value Object, the EPW arithmetic and the anchor
 * question belong to **7B**; the deletion guard belongs to **7C**. Neither is
 * asserted here — 7A is deliberately inert: nothing consumes it.
 *
 * The governing rules under test:
 *  - **Constitutional Policy 2:** EPW = Contestation Window + Maximum Adjudication
 *    Duration + Legal Safety Margin. CW and LSM had no home; this slice gives them one.
 *  - **AP-1 (fail closed):** a missing, non-numeric or non-positive duration is a
 *    CONFIGURATION ERROR. This adapter must never default, clamp or substitute — the
 *    values are Q-2's, and infrastructure deciding one *is* the violation of policy
 *    ownership.
 *  - **AP-2 (one home per parameter):** MAD keeps exactly one home,
 *    `config/adjudication.php`. This context must not carry a second copy.
 *  - **R-44 (A-1 ratified):** Election declares its **own consumer-side port** in its
 *    own language. It must NOT import Adjudication's port — that is a direct
 *    cross-context code dependency, which TP-1 forbids and Deptrac would fail.
 *
 * Provenance: WP-7 plan §5 slice 7A · implementation guard commission (C-1) ·
 * architecture–enforcement alignment commission (option (d), R-44).
 */
final class EvidencePreservationDurationsTest extends TestCase
{
    private function durations(): EvidencePreservationDurations
    {
        return $this->app->make(EvidencePreservationDurations::class);
    }

    private function config(): Config
    {
        return $this->app->make(Config::class);
    }

    // ── KEYSTONE 1: the Contestation Window resolves per election type ───────

    public function test_the_contestation_window_resolves_per_election_type(): void
    {
        $this->config()->set('election_preservation.per_election_type', [
            'board_election' => ['contestation_window_days' => 30],
        ]);

        $cw = $this->durations()->contestationWindow('board_election');

        // NB `DateInterval::$days` is false unless the interval came from a diff();
        // the day COMPONENT of a constructed interval is `->d`. (WP-6's lesson.)
        $this->assertSame(30, $cw->d);
    }

    // ── KEYSTONE 2: the Legal Safety Margin resolves per election type ───────

    public function test_the_legal_safety_margin_resolves_per_election_type(): void
    {
        $this->config()->set('election_preservation.per_election_type', [
            'board_election' => ['legal_safety_margin_days' => 14],
        ]);

        $lsm = $this->durations()->legalSafetyMargin('board_election');

        $this->assertSame(14, $lsm->d);
    }

    // ── KEYSTONE 3: organisation override beats the election-type value ─────

    /**
     * Precedence is organisation → election type → default, mirroring the shape
     * `ConfiguredAdjudicationDurations` already resolves. The narrower scope wins.
     */
    public function test_an_organisation_override_takes_precedence_over_the_election_type(): void
    {
        $this->config()->set('election_preservation.per_election_type', [
            'board_election' => ['contestation_window_days' => 30],
        ]);
        $this->config()->set('election_preservation.per_organisation', [
            'org-7' => ['contestation_window_days' => 45],
        ]);

        $cw = $this->durations()->contestationWindow('board_election', 'org-7');

        $this->assertSame(45, $cw->d, 'the narrower (organisation) scope must win');
    }

    // ── KEYSTONE 4: fail closed on a MISSING value — throws, never defaults ──

    /**
     * AP-1. The adapter has no business value of its own to fall back to. Absence is a
     * configuration error, and substituting a number here would be infrastructure
     * deciding business policy — the exact defect corrected in WP-6.
     */
    public function test_a_missing_contestation_window_throws_rather_than_defaulting(): void
    {
        $this->config()->set('election_preservation.contestation_window_days', null);
        $this->config()->set('election_preservation.per_election_type', []);
        $this->config()->set('election_preservation.per_organisation', []);

        $this->expectException(RuntimeException::class);

        $this->durations()->contestationWindow('board_election');
    }

    // ── KEYSTONE 5: fail closed on a NON-NUMERIC value ──────────────────────

    public function test_a_non_numeric_legal_safety_margin_throws(): void
    {
        $this->config()->set('election_preservation.legal_safety_margin_days', 'thirty');
        $this->config()->set('election_preservation.per_election_type', []);
        $this->config()->set('election_preservation.per_organisation', []);

        $this->expectException(RuntimeException::class);

        $this->durations()->legalSafetyMargin('board_election');
    }

    // ── KEYSTONE 6: fail closed on a NON-POSITIVE duration — no clamping ────

    /**
     * The AP-1 keystone proper. A zero or negative window must THROW, not be silently
     * lifted to 1 by a `max()`. The clamp is what made AP-1 invisible to every gate.
     */
    public function test_a_non_positive_contestation_window_throws_and_is_not_clamped(): void
    {
        $this->config()->set('election_preservation.contestation_window_days', 0);
        $this->config()->set('election_preservation.per_election_type', []);
        $this->config()->set('election_preservation.per_organisation', []);

        $this->expectException(RuntimeException::class);

        $this->durations()->contestationWindow('board_election');
    }

    // ── KEYSTONE 7: MAD is CONSUMED from its one home, never copied (AP-2) ──

    /**
     * The port speaks Election's language and offers all three Policy 2 terms — but the
     * MAD it returns must come from `config/adjudication.php`, MAD's single home. A
     * `maximum_adjudication_duration_days` key under `election_preservation` would be
     * precisely the Decision Duplication corrected in WP-6.
     */
    public function test_the_maximum_adjudication_duration_is_consumed_and_not_copied(): void
    {
        $this->config()->set('adjudication.maximum_adjudication_duration_days', 60);

        $mad = $this->durations()->maximumAdjudicationDuration('board_election');

        $this->assertSame(60, $mad->d, 'MAD must be read from its one home');
        $this->assertNull(
            $this->config()->get('election_preservation.maximum_adjudication_duration_days'),
            'a retention-side MAD key would be a second home for a value that has exactly one (AP-2)',
        );
    }

    // ── R-D1: the two adapters must never disagree about MAD ────────────────

    /**
     * The honest cost of the consumer-side port (R-44): the precedence rule
     * organisation → election type → default now exists in two adapters, so the VALUE
     * keeps one home while the RESOLUTION could drift. This keystone protects AP-2's
     * INTENT rather than its letter.
     */
    public function test_both_adapters_resolve_the_same_mad_for_the_same_scope(): void
    {
        $this->config()->set('adjudication.maximum_adjudication_duration_days', 60);
        $this->config()->set('adjudication.per_election_type', [
            'board_election' => ['maximum_adjudication_duration_days' => 90],
        ]);

        $viaAdjudication = $this->app->make(AdjudicationDurations::class)
            ->maximumAdjudicationDuration('board_election');
        $viaElection = $this->durations()
            ->maximumAdjudicationDuration('board_election');

        $this->assertSame(
            $viaAdjudication->d,
            $viaElection->d,
            'the two adapters must never disagree about MAD for the same scope',
        );
    }
}
