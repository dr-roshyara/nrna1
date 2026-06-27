<?php

namespace Tests\Unit\Domain\Election\Security\Event;

use App\Domain\Election\Security\Event\ConstitutionalDenialIssued;
use App\Domain\Election\Security\Event\LegitimacyEvaluated;
use App\Domain\Election\Security\Event\LegitimacyGranted;
use App\Domain\Election\Security\LegitimacyOutcome;
use PHPUnit\Framework\TestCase;

class SovereignEventsTest extends TestCase
{
    public function test_legitimacy_evaluated_immutable(): void
    {
        $evaluated = new LegitimacyEvaluated(
            electionId: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
            outcome: LegitimacyOutcome::Denied,
            evidenceEnvelopeHash: hash('sha256', 'envelope'),
            policySequenceHash: hash('sha256', 'policy_v1'),
            denialReason: 'Network limit exceeded',
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame('election_001', $evaluated->electionId);
        $this->assertSame(LegitimacyOutcome::Denied, $evaluated->outcome);
        $this->assertSame('Network limit exceeded', $evaluated->denialReason);
        $this->assertTrue((new \ReflectionClass($evaluated))->isReadOnly(),
            'Sovereign events must be readonly — immutable after emission');
    }

    public function test_constitutional_denial_issued_carries_enforcement_semantics(): void
    {
        $denial = new ConstitutionalDenialIssued(
            electionId: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
            outcome: LegitimacyOutcome::Denied,
            reason: 'D.0.3a constitutional gate denied',
            evidenceEnvelopeHash: hash('sha256', 'envelope'),
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame(LegitimacyOutcome::Denied, $denial->outcome);
        $this->assertStringContainsString('constitutional', $denial->reason);
    }

    public function test_legitimacy_granted_is_distinct_from_evaluated(): void
    {
        $granted = new LegitimacyGranted(
            electionId: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
            evidenceEnvelopeHash: hash('sha256', 'envelope'),
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame('election_001', $granted->electionId);
        // LegitimacyGranted has no outcome field — it IS the grant
        // (complements ConstitutionalDenialIssued for complete observability)
    }

    public function test_sovereign_events_have_no_laravel_dependencies(): void
    {
        $reflection = new \ReflectionClass(LegitimacyEvaluated::class);
        $filename = $reflection->getFileName();
        $contents = file_get_contents($filename);

        $this->assertStringNotContainsString('use Illuminate', $contents,
            'Sovereign events must have zero Laravel dependencies — pure domain only');
    }
}
