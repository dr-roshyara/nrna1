<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\Domain;

use App\Contexts\Election\Domain\EvidencePreservationWindow;
use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * WP-7 Slice 7B — the Evidence Preservation Window value object (R-58).
 *
 * Constitutional Policy 2: **EPW = Contestation Window + Maximum Adjudication Duration
 * + Legal Safety Margin**, attached to the election instance. Policy 2 names it
 * *"a single domain concept, not a configuration value"*, which is why it is a Value
 * Object owned by the Election context rather than an application computation.
 *
 * SCOPE — 7B only. This slice delivers the value and its arithmetic; **nothing consumes
 * it**. The deletion guard is 7C and is not authorized.
 *
 * K7 and K9 live with 7B's application-layer collaborator, not here: the VO takes
 * business values only, so it never resolves a port (K7) and can never observe a
 * missing anchor (K9 — P7B-2).
 *
 * Traceability: Policy 2 · EPIC-004K §142 · R-44 (Election's own port) · R-56 (plan) ·
 * R-57 (plan mechanism corrected) · R-58 (execution authorized) · construction
 * commission (private ctor + named static factory; business values only).
 */
final class EvidencePreservationWindowTest extends TestCase
{
    private const ANCHOR = '2026-03-01T00:00:00+00:00';

    private function anchor(): DateTimeImmutable
    {
        return new DateTimeImmutable(self::ANCHOR);
    }

    /** CW 30 + MAD 60 + LSM 30 = 120 days. */
    private function window(): EvidencePreservationWindow
    {
        return EvidencePreservationWindow::forElection(
            $this->anchor(),
            new DateInterval('P30D'),
            new DateInterval('P60D'),
            new DateInterval('P30D'),
        );
    }

    // ── K1: constructed only through its named factory ──────────────────────

    /**
     * The house idiom (`ChallengeRef::fromString`, `EvidenceSet::fromRefs`,
     * `ContestedOutcomeRef::of`): a private constructor, so validity cannot be bypassed.
     */
    public function test_k1_the_window_is_constructed_only_through_its_named_factory(): void
    {
        $constructor = (new ReflectionClass(EvidencePreservationWindow::class))->getConstructor();

        $this->assertNotNull($constructor);
        $this->assertTrue($constructor->isPrivate(), 'the constructor must be private');
        $this->assertTrue(
            method_exists(EvidencePreservationWindow::class, 'forElection'),
            'the factory states a FACT (forElection), never a procedure',
        );
    }

    // ── K2: EPW = CW + MAD + LSM (§142) ─────────────────────────────────────

    public function test_k2_the_window_is_the_sum_of_all_three_policy_2_terms(): void
    {
        $this->assertEquals(
            $this->anchor()->add(new DateInterval('P120D')),
            $this->window()->closesAt(),
            'EPW must be CW + MAD + LSM — dropping any term shortens constitutional retention',
        );
    }

    // ── K3/K4/K5: the window's extent ───────────────────────────────────────

    public function test_k3_the_window_is_open_at_an_instant_inside_it(): void
    {
        $this->assertTrue($this->window()->isOpenAt($this->anchor()->add(new DateInterval('P119D'))));
    }

    public function test_k4_the_window_is_closed_at_an_instant_after_it(): void
    {
        $this->assertFalse($this->window()->isOpenAt($this->anchor()->add(new DateInterval('P121D'))));
    }

    public function test_k5_the_window_is_closed_at_an_instant_before_its_anchor(): void
    {
        $this->assertFalse($this->window()->isOpenAt($this->anchor()->sub(new DateInterval('P1D'))));
    }

    // ── K6: business values only ────────────────────────────────────────────

    /**
     * The dependency rule from the construction commission: the constructor takes an
     * anchor and three durations — **never a port, config, Eloquent model or clock**.
     * The instant is an ARGUMENT to `isOpenAt()`, which is why no clock is injected.
     */
    public function test_k6_the_constructor_accepts_business_values_only(): void
    {
        $permitted = [DateTimeImmutable::class, DateInterval::class];

        foreach ((new ReflectionClass(EvidencePreservationWindow::class))->getConstructor()->getParameters() as $parameter) {
            $this->assertContains(
                (string) $parameter->getType(),
                $permitted,
                "constructor parameter \${$parameter->getName()} is not a business value",
            );
        }
    }

    // ── K8: fail closed — a non-positive duration throws (AP-1) ─────────────

    /**
     * AP-1 in the domain: a zero or negative term is a CONFIGURATION ERROR. The value
     * must reject it rather than clamp it — a business floor is Q-2's, never ours.
     */
    public function test_k8_a_non_positive_duration_is_rejected_and_never_clamped(): void
    {
        $this->expectException(InvalidArgumentException::class);

        EvidencePreservationWindow::forElection(
            $this->anchor(),
            new DateInterval('PT0S'),
            new DateInterval('P60D'),
            new DateInterval('P30D'),
        );
    }

    // ── K10: immutable, and no identity ─────────────────────────────────────

    public function test_k10_the_window_is_immutable(): void
    {
        foreach ((new ReflectionClass(EvidencePreservationWindow::class))->getProperties() as $property) {
            $this->assertTrue($property->isReadOnly(), "property \${$property->getName()} must be readonly");
        }
    }

    /** Two windows built from the same values ARE the same window — no identity. */
    public function test_k10_two_windows_with_the_same_inputs_are_equal(): void
    {
        $this->assertEquals($this->window(), $this->window());
    }
}
