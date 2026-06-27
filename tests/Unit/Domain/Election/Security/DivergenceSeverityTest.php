<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\DivergenceSeverity;
use App\Domain\Election\Security\DivergenceType;
use App\Domain\Election\Security\LegitimacyOutcome;
use PHPUnit\Framework\TestCase;

/**
 * DivergenceSeverityTest
 *
 * Verifies the divergence severity enum:
 * - Five severity levels with string values
 * - fromDivergence() correctly maps type+outcome to severity
 * - blocksTransfer() returns correct boolean for each level
 * - requiresInvestigation() returns correct boolean for each level
 * - CRITICAL and EXISTENTIAL block transfer
 * - HIGH requires investigation but does not block transfer
 */
class DivergenceSeverityTest extends TestCase
{
    public function test_has_five_levels(): void
    {
        $cases = DivergenceSeverity::cases();
        $this->assertCount(5, $cases);
    }

    public function test_string_values(): void
    {
        $this->assertSame('info', DivergenceSeverity::Info->value);
        $this->assertSame('warning', DivergenceSeverity::Warning->value);
        $this->assertSame('high', DivergenceSeverity::High->value);
        $this->assertSame('critical', DivergenceSeverity::Critical->value);
        $this->assertSame('existential', DivergenceSeverity::Existential->value);
    }

    /** @dataProvider divergenceSeverityProvider */
    public function test_from_divergence(
        DivergenceType $type,
        LegitimacyOutcome $legacy,
        LegitimacyOutcome $constitutional,
        DivergenceSeverity $expected,
    ): void {
        $severity = DivergenceSeverity::fromDivergence($type, $legacy, $constitutional);
        $this->assertSame($expected, $severity);
    }

    public static function divergenceSeverityProvider(): array
    {
        return [
            'ProceduralOverreach is Critical' => [
                DivergenceType::ProceduralOverreach,
                LegitimacyOutcome::Denied,
                LegitimacyOutcome::Allowed,
                DivergenceSeverity::Critical,
            ],
            'SovereigntyLeak is Critical' => [
                DivergenceType::SovereigntyLeak,
                LegitimacyOutcome::Allowed,
                LegitimacyOutcome::Denied,
                DivergenceSeverity::Critical,
            ],
            'Exposure is High' => [
                DivergenceType::Exposure,
                LegitimacyOutcome::Allowed,
                LegitimacyOutcome::Investigate,
                DivergenceSeverity::High,
            ],
            'ConstitutionalUncertainty is High' => [
                DivergenceType::ConstitutionalUncertainty,
                LegitimacyOutcome::Denied,
                LegitimacyOutcome::Deferred,
                DivergenceSeverity::High,
            ],
            'LineageMismatch is Warning' => [
                DivergenceType::LineageMismatch,
                LegitimacyOutcome::Denied,
                LegitimacyOutcome::Denied,
                DivergenceSeverity::Warning,
            ],
            'TopologyMismatch is Info' => [
                DivergenceType::TopologyMismatch,
                LegitimacyOutcome::Allowed,
                LegitimacyOutcome::Allowed,
                DivergenceSeverity::Info,
            ],
        ];
    }

    /** @dataProvider blocksTransferProvider */
    public function test_blocks_transfer(DivergenceSeverity $severity, bool $expected): void
    {
        $this->assertSame($expected, $severity->blocksTransfer());
    }

    public static function blocksTransferProvider(): array
    {
        return [
            'Info does not block'        => [DivergenceSeverity::Info, false],
            'Warning does not block'     => [DivergenceSeverity::Warning, false],
            'High does not block'        => [DivergenceSeverity::High, false],
            'Critical blocks'            => [DivergenceSeverity::Critical, true],
            'Existential blocks'         => [DivergenceSeverity::Existential, true],
        ];
    }

    /** @dataProvider requiresInvestigationProvider */
    public function test_requires_investigation(DivergenceSeverity $severity, bool $expected): void
    {
        $this->assertSame($expected, $severity->requiresInvestigation());
    }

    public static function requiresInvestigationProvider(): array
    {
        return [
            'Info does not require investigation'   => [DivergenceSeverity::Info, false],
            'Warning does not require investigation' => [DivergenceSeverity::Warning, false],
            'High requires investigation'           => [DivergenceSeverity::High, true],
            'Critical requires investigation'       => [DivergenceSeverity::Critical, true],
            'Existential requires investigation'    => [DivergenceSeverity::Existential, true],
        ];
    }

    public function test_from_divergence_defaults_to_warning_for_unknown_type(): void
    {
        // Default branch — should not normally occur with valid DivergenceType values
        // This tests defensive fallback behavior
        $severity = DivergenceSeverity::fromDivergence(
            DivergenceType::LineageMismatch,
            LegitimacyOutcome::Deferred,
            LegitimacyOutcome::Investigate,
        );
        $this->assertSame(DivergenceSeverity::Warning, $severity);
    }

    public function test_critical_and_existential_are_only_blockers(): void
    {
        foreach (DivergenceSeverity::cases() as $severity) {
            if (in_array($severity, [DivergenceSeverity::Critical, DivergenceSeverity::Existential], true)) {
                $this->assertTrue($severity->blocksTransfer());
            } else {
                $this->assertFalse($severity->blocksTransfer());
            }
        }
    }

    public function test_high_critical_existential_require_investigation(): void
    {
        foreach (DivergenceSeverity::cases() as $severity) {
            if (in_array($severity, [DivergenceSeverity::High, DivergenceSeverity::Critical, DivergenceSeverity::Existential], true)) {
                $this->assertTrue($severity->requiresInvestigation());
            } else {
                $this->assertFalse($severity->requiresInvestigation());
            }
        }
    }

    public function test_has_no_behavioral_methods_beyond_value_methods(): void
    {
        $reflection = new \ReflectionClass(DivergenceSeverity::class);
        $builtIns = ['cases', 'from', 'tryFrom'];
        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => !in_array($m->getName(), [...$builtIns, 'fromDivergence', 'blocksTransfer', 'requiresInvestigation'])
        );
        $this->assertCount(0, $methods, 'DivergenceSeverity must only have fromDivergence, blocksTransfer, requiresInvestigation beyond enum built-ins');
    }
}
