<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\DivergenceSeverity;
use App\Domain\Election\Security\DivergenceType;
use App\Domain\Election\Security\LegitimacyOutcome;
use App\Domain\Election\Security\SovereigntyDivergenceRecord;
use PHPUnit\Framework\TestCase;

/**
 * SovereigntyDivergenceRecordTest
 *
 * Verifies the divergence tracking value object:
 * - Readonly frozen record (no behavioral methods)
 * - Typed DivergenceType and DivergenceSeverity enums
 * - Correct divergence detection (matched vs mismatched)
 * - All fields accessible
 * - Snapshot hash preserved for replay archaeology
 */
class SovereigntyDivergenceRecordTest extends TestCase
{
    public function test_is_readonly(): void
    {
        $reflection = new \ReflectionClass(SovereigntyDivergenceRecord::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function test_records_matched_outcome(): void
    {
        $record = new SovereigntyDivergenceRecord(
            divergenceType: DivergenceType::TopologyMismatch,
            legacyOutcome: LegitimacyOutcome::Allowed,
            constitutionalOutcome: LegitimacyOutcome::Allowed,
            matched: true,
            severity: DivergenceSeverity::Info,
            snapshotHash: 'abc123',
            context: ['election_id' => 1, 'user_id' => 42],
            observedAt: new \DateTimeImmutable('2026-05-28 12:00:00'),
        );

        $this->assertTrue($record->matched);
        $this->assertSame(DivergenceType::TopologyMismatch, $record->divergenceType);
        $this->assertSame('topology_mismatch', $record->divergenceType->value);
        $this->assertSame(DivergenceSeverity::Info, $record->severity);
        $this->assertSame(LegitimacyOutcome::Allowed, $record->legacyOutcome);
        $this->assertSame(LegitimacyOutcome::Allowed, $record->constitutionalOutcome);
        $this->assertSame('abc123', $record->snapshotHash);
        $this->assertNull($record->divergenceReason);
    }

    public function test_records_procedural_overreach(): void
    {
        // Legacy denied, constitutional would allow — false illegitimacy
        $record = new SovereigntyDivergenceRecord(
            divergenceType: DivergenceType::ProceduralOverreach,
            legacyOutcome: LegitimacyOutcome::Denied,
            constitutionalOutcome: LegitimacyOutcome::Allowed,
            matched: false,
            severity: DivergenceSeverity::Critical,
            snapshotHash: 'def456',
            context: ['election_id' => 2, 'user_id' => 99],
            observedAt: new \DateTimeImmutable(),
            divergenceReason: 'Legacy denied but constitutional allowed',
        );

        $this->assertFalse($record->matched);
        $this->assertSame(DivergenceType::ProceduralOverreach, $record->divergenceType);
        $this->assertSame(DivergenceSeverity::Critical, $record->severity);
        $this->assertTrue($record->severity->blocksTransfer());
        $this->assertTrue($record->severity->requiresInvestigation());
        $this->assertSame(LegitimacyOutcome::Denied, $record->legacyOutcome);
        $this->assertSame(LegitimacyOutcome::Allowed, $record->constitutionalOutcome);
        $this->assertSame('def456', $record->snapshotHash);
        $this->assertSame('Legacy denied but constitutional allowed', $record->divergenceReason);
    }

    public function test_records_sovereignty_leak(): void
    {
        // Legacy allowed, constitutional would deny — false legitimacy
        $record = new SovereigntyDivergenceRecord(
            divergenceType: DivergenceType::SovereigntyLeak,
            legacyOutcome: LegitimacyOutcome::Allowed,
            constitutionalOutcome: LegitimacyOutcome::Denied,
            matched: false,
            severity: DivergenceSeverity::Critical,
            snapshotHash: 'sovereignty-leak-hash',
            context: ['election_id' => 3, 'user_id' => 55],
            observedAt: new \DateTimeImmutable(),
            divergenceReason: 'Legacy allowed but constitutional denied',
        );

        $this->assertFalse($record->matched);
        $this->assertSame(DivergenceType::SovereigntyLeak, $record->divergenceType);
        $this->assertSame(DivergenceSeverity::Critical, $record->severity);
        $this->assertTrue($record->severity->blocksTransfer());
        $this->assertSame('sovereignty-leak-hash', $record->snapshotHash);
        $this->assertSame('Legacy allowed but constitutional denied', $record->divergenceReason);
    }

    public function test_records_constitutional_uncertainty(): void
    {
        $record = new SovereigntyDivergenceRecord(
            divergenceType: DivergenceType::ConstitutionalUncertainty,
            legacyOutcome: LegitimacyOutcome::Denied,
            constitutionalOutcome: LegitimacyOutcome::Deferred,
            matched: false,
            severity: DivergenceSeverity::High,
            snapshotHash: 'uncertainty-hash',
            context: ['election_id' => 4, 'user_id' => 77],
            observedAt: new \DateTimeImmutable(),
            divergenceReason: 'Constitutional evaluation uncertain, deferred for review',
        );

        $this->assertFalse($record->matched);
        $this->assertSame(DivergenceType::ConstitutionalUncertainty, $record->divergenceType);
        $this->assertSame(DivergenceSeverity::High, $record->severity);
        $this->assertFalse($record->severity->blocksTransfer());
        $this->assertTrue($record->severity->requiresInvestigation());
    }

    public function test_records_divergence_context(): void
    {
        $context = [
            'election_id' => 5,
            'user_id' => 123,
            'route' => 'slug.vote.submit',
        ];

        $record = new SovereigntyDivergenceRecord(
            divergenceType: DivergenceType::LineageMismatch,
            legacyOutcome: LegitimacyOutcome::Denied,
            constitutionalOutcome: LegitimacyOutcome::Denied,
            matched: false,
            severity: DivergenceSeverity::Warning,
            snapshotHash: 'snapshot-hash-xyz',
            context: $context,
            observedAt: new \DateTimeImmutable(),
            divergenceReason: 'Both deny, different constitutional basis',
        );

        $this->assertSame($context, $record->context);
        $this->assertSame('snapshot-hash-xyz', $record->snapshotHash);
        $this->assertSame('Both deny, different constitutional basis', $record->divergenceReason);
        $this->assertSame(DivergenceSeverity::Warning, $record->severity);
        $this->assertFalse($record->severity->blocksTransfer());
    }

    public function test_has_no_behavioral_methods(): void
    {
        $reflection = new \ReflectionClass(SovereigntyDivergenceRecord::class);
        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => $m->getName() !== '__construct'
        );
        $this->assertCount(0, $methods, 'Divergence record must be pure data — no behavioral methods');
    }

    public function test_all_outcome_combinations_are_recordable(): void
    {
        $outcomes = LegitimacyOutcome::cases();
        $types = [DivergenceType::TopologyMismatch, DivergenceType::ProceduralOverreach,
                  DivergenceType::SovereigntyLeak, DivergenceType::LineageMismatch];

        foreach ($outcomes as $legacy) {
            foreach ($outcomes as $constitutional) {
                $record = new SovereigntyDivergenceRecord(
                    divergenceType: $types[array_rand($types)],
                    legacyOutcome: $legacy,
                    constitutionalOutcome: $constitutional,
                    matched: $legacy === $constitutional,
                    severity: DivergenceSeverity::Warning,
                    snapshotHash: 'hash',
                    context: [],
                    observedAt: new \DateTimeImmutable(),
                );

                $this->assertSame($legacy === $constitutional, $record->matched);
            }
        }
    }
}
