<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\DivergenceType;
use App\Domain\Election\Security\LegitimacyOutcome;
use PHPUnit\Framework\TestCase;

/**
 * DivergenceTypeTest
 *
 * Verifies the divergence classification enum:
 * - Six divergence types with string values
 * - classify() correctly maps all outcome pairs
 * - Same-outcome-with-different-basis defaults to TopologyMismatch
 * - All outcome combinations produce a valid classification
 */
class DivergenceTypeTest extends TestCase
{
    public function test_has_six_types(): void
    {
        $cases = DivergenceType::cases();
        $this->assertCount(6, $cases);
    }

    public function test_string_values(): void
    {
        $this->assertSame('procedural_overreach', DivergenceType::ProceduralOverreach->value);
        $this->assertSame('sovereignty_leak', DivergenceType::SovereigntyLeak->value);
        $this->assertSame('lineage_mismatch', DivergenceType::LineageMismatch->value);
        $this->assertSame('topology_mismatch', DivergenceType::TopologyMismatch->value);
        $this->assertSame('constitutional_uncertainty', DivergenceType::ConstitutionalUncertainty->value);
        $this->assertSame('exposure', DivergenceType::Exposure->value);
    }

    public function test_classify_procedural_overreach(): void
    {
        // Legacy denied, constitutional would allow — false illegitimacy
        $type = DivergenceType::classify(
            legacy: LegitimacyOutcome::Denied,
            constitutional: LegitimacyOutcome::Allowed,
        );
        $this->assertSame(DivergenceType::ProceduralOverreach, $type);
    }

    public function test_classify_sovereignty_leak(): void
    {
        // Legacy allowed, constitutional would deny — false legitimacy
        $type = DivergenceType::classify(
            legacy: LegitimacyOutcome::Allowed,
            constitutional: LegitimacyOutcome::Denied,
        );
        $this->assertSame(DivergenceType::SovereigntyLeak, $type);
    }

    public function test_classify_lineage_mismatch(): void
    {
        // Both deny — different constitutional basis
        $type = DivergenceType::classify(
            legacy: LegitimacyOutcome::Denied,
            constitutional: LegitimacyOutcome::Denied,
        );
        $this->assertSame(DivergenceType::LineageMismatch, $type);
    }

    public function test_classify_topology_mismatch(): void
    {
        // Both allow — different evidence topology
        $type = DivergenceType::classify(
            legacy: LegitimacyOutcome::Allowed,
            constitutional: LegitimacyOutcome::Allowed,
        );
        $this->assertSame(DivergenceType::TopologyMismatch, $type);
    }

    public function test_classify_constitutional_uncertainty(): void
    {
        // Constitutional deferred — review class mismatch
        $type = DivergenceType::classify(
            legacy: LegitimacyOutcome::Denied,
            constitutional: LegitimacyOutcome::Deferred,
        );
        $this->assertSame(DivergenceType::ConstitutionalUncertainty, $type);
    }

    public function test_classify_exposure(): void
    {
        // Constitutional flagged for investigation — replay risk
        $type = DivergenceType::classify(
            legacy: LegitimacyOutcome::Allowed,
            constitutional: LegitimacyOutcome::Investigate,
        );
        $this->assertSame(DivergenceType::Exposure, $type);
    }

    public function test_classify_deferred_with_allowed_legacy(): void
    {
        $type = DivergenceType::classify(
            legacy: LegitimacyOutcome::Allowed,
            constitutional: LegitimacyOutcome::Deferred,
        );
        $this->assertSame(DivergenceType::ConstitutionalUncertainty, $type);
    }

    public function test_classify_investigate_with_denied_legacy(): void
    {
        $type = DivergenceType::classify(
            legacy: LegitimacyOutcome::Denied,
            constitutional: LegitimacyOutcome::Investigate,
        );
        $this->assertSame(DivergenceType::Exposure, $type);
    }

    public function test_all_outcome_pairs_classify_without_exception(): void
    {
        $outcomes = LegitimacyOutcome::cases();
        $classified = [];

        foreach ($outcomes as $legacy) {
            foreach ($outcomes as $constitutional) {
                $type = DivergenceType::classify($legacy, $constitutional);
                $classified[$legacy->value . '->' . $constitutional->value] = $type;
                $this->assertInstanceOf(DivergenceType::class, $type);
            }
        }

        // All 16 pairs classified — verify no duplicates where diverging
        $this->assertCount(16, $classified);
    }

    public function test_has_no_behavioral_methods_beyond_classify(): void
    {
        $reflection = new \ReflectionClass(DivergenceType::class);
        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => !in_array($m->getName(), ['cases', 'from', 'tryFrom', 'classify'])
        );
        $this->assertCount(0, $methods, 'DivergenceType must only have classify() beyond enum built-ins');
    }
}
