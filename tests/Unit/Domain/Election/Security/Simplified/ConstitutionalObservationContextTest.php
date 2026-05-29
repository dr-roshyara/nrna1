<?php

namespace Tests\Unit\Domain\Election\Security\Simplified;

use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ConstitutionalObservationContextTest extends TestCase
{
    public function test_holds_all_signals_without_ranking(): void
    {
        $signal1 = OverlaySignal::contextStable('overlay1', 'Stable condition', []);
        $signal2 = OverlaySignal::evidenceInconsistent('overlay2', 'Inconsistent evidence', []);

        $context = new ConstitutionalObservationContext([$signal1, $signal2]);

        self::assertCount(2, $context->observations);
        self::assertSame($signal1, $context->observations[0]);
        self::assertSame($signal2, $context->observations[1]);
    }

    public function test_count_returns_correct_number(): void
    {
        $signals = [
            OverlaySignal::contextStable('o1', 'Stable', []),
            OverlaySignal::evidenceInconsistent('o2', 'Inconsistent', []),
            OverlaySignal::attestationPresent('o3', 'Attestation', []),
        ];

        $context = new ConstitutionalObservationContext($signals);

        self::assertSame(3, $context->count());
    }

    public function test_all_returns_array_of_overlay_signals(): void
    {
        $signals = [
            OverlaySignal::contextStable('o1', 'Stable', []),
            OverlaySignal::evidenceInconsistent('o2', 'Inconsistent', []),
        ];

        $context = new ConstitutionalObservationContext($signals);
        $all = $context->all();

        self::assertIsArray($all);
        self::assertCount(2, $all);
        self::assertSame($signals[0], $all[0]);
        self::assertSame($signals[1], $all[1]);
    }

    public function test_empty_context_has_zero_count(): void
    {
        $context = new ConstitutionalObservationContext([]);

        self::assertSame(0, $context->count());
    }

    public function test_conflicting_signals_both_preserved(): void
    {
        $stable = OverlaySignal::contextStable('o1', 'No concerns', []);
        $inconsistent = OverlaySignal::evidenceInconsistent('o2', 'Evidence conflict', []);

        $context = new ConstitutionalObservationContext([$stable, $inconsistent]);

        self::assertCount(2, $context->observations);
        self::assertSame($stable, $context->observations[0]);
        self::assertSame($inconsistent, $context->observations[1]);
    }

    public function test_no_ranking_methods_exist(): void
    {
        $reflection = new ReflectionClass(ConstitutionalObservationContext::class);
        $methods = $reflection->getMethods();
        $methodNames = array_map(fn($m) => $m->name, $methods);

        $forbiddenNames = ['highestConcern', 'lowestWeight', 'strongestSignal', 'dominantConcern'];
        foreach ($forbiddenNames as $forbidden) {
            self::assertNotContains($forbidden, $methodNames, "Method $forbidden violates Article 6 (ordering neutral)");
        }
    }

    public function test_no_authority_methods_exist(): void
    {
        $reflection = new ReflectionClass(ConstitutionalObservationContext::class);
        $methods = $reflection->getMethods();
        $methodNames = array_map(fn($m) => $m->name, $methods);

        $forbiddenNames = ['allow', 'deny', 'authorize', 'grant'];
        foreach ($forbiddenNames as $forbidden) {
            self::assertNotContains($forbidden, $methodNames, "Method $forbidden violates Article 1 (non-sovereignty)");
        }
    }

    public function test_ordering_is_non_sovereign(): void
    {
        // CORRECTED (R3): Test resolver outcome invariance, not collection ordering.
        // This is a constitutional invariant test: identical evidence across different
        // signal orderings must produce identical CapabilityDecision in EvidenceCapabilityPolicy.
        //
        // This test verifies the invariant by creating two contexts with same signals
        // in different order, and asserting that both contexts are valid containers for
        // the resolver to interpret. The resolver's decision-making must not depend on
        // signal order.

        $signal1 = OverlaySignal::contextStable('o1', 'Stable', []);
        $signal2 = OverlaySignal::evidenceInconsistent('o2', 'Inconsistent', []);

        // Original order
        $context1 = new ConstitutionalObservationContext([$signal1, $signal2]);

        // Reversed order
        $context2 = new ConstitutionalObservationContext([$signal2, $signal1]);

        // Both contexts contain the same signals (order must not affect content preservation)
        self::assertCount(2, $context1->observations);
        self::assertCount(2, $context2->observations);

        // Both contexts preserve the same signal set, just in different order
        self::assertSame($signal1, $context1->observations[0]);
        self::assertSame($signal2, $context1->observations[1]);
        self::assertSame($signal2, $context2->observations[0]);
        self::assertSame($signal1, $context2->observations[1]);

        // FULL invariant test (resolver outcome independence) is tested in
        // ConstitutionalSemanticRegressionTests::test_signal_ordering_has_no_sovereign_meaning()
        // which verifies EvidenceCapabilityPolicy produces identical decisions across
        // all signal ordering permutations.
    }

    public function test_empty_factory_returns_empty_context(): void
    {
        $context = ConstitutionalObservationContext::empty();

        self::assertTrue($context->isEmpty());
        self::assertSame(0, $context->count());
    }

    public function test_is_empty_returns_correct_state(): void
    {
        $emptyContext = new ConstitutionalObservationContext([]);
        self::assertTrue($emptyContext->isEmpty());

        $filledContext = new ConstitutionalObservationContext([
            OverlaySignal::contextStable('o1', 'Stable', []),
        ]);
        self::assertFalse($filledContext->isEmpty());
    }

    public function test_observations_property_is_readonly(): void
    {
        $context = new ConstitutionalObservationContext([]);

        // Attempting to modify should fail
        $reflection = new ReflectionClass($context);
        $property = $reflection->getProperty('observations');

        self::assertTrue($property->isReadonly(), 'observations property must be readonly');
    }
}
