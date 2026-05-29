<?php

namespace Tests\Unit\Domain\Election\Security\Event;

use App\Domain\Election\Security\Event\ConstitutionalFallbackActivated;
use App\Domain\Election\Security\Event\DivergenceObserved;
use App\Domain\Election\Security\Event\ObservationRecorded;
use App\Domain\Election\Security\Event\SovereigntyBoundaryCrossed;
use App\Domain\Election\Security\LegitimacyOutcome;
use PHPUnit\Framework\TestCase;

class MigrationAndObservationalEventsTest extends TestCase
{
    // =========================================================================
    // Migration Events
    // =========================================================================

    public function test_sovereignty_boundary_crossed_records_divergent_outcomes(): void
    {
        $event = new SovereigntyBoundaryCrossed(
            electionId: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
            constitutionalOutcome: LegitimacyOutcome::Denied,
            legacyOutcome: LegitimacyOutcome::Allowed,
            divergenceType: 'constitutional_denies_legacy_allows',
            evidenceEnvelopeHash: hash('sha256', 'envelope'),
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame(LegitimacyOutcome::Denied, $event->constitutionalOutcome);
        $this->assertSame(LegitimacyOutcome::Allowed, $event->legacyOutcome);
        $this->assertNotSame(
            $event->constitutionalOutcome,
            $event->legacyOutcome,
            'Sovereignty boundary crossing requires divergent outcomes',
        );
    }

    public function test_constitutional_fallback_activated_carries_rollback_context(): void
    {
        $event = new ConstitutionalFallbackActivated(
            reason: 'F3 test detected replay hash divergence',
            previousPhase: 'D.0.3a',
            fallbackPhase: 'D.0.1',
            context: [
                'trigger' => 'replay_divergence',
                'divergence_count' => 1,
                'evidence_envelope' => hash('sha256', 'envelope'),
            ],
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertStringContainsString('replay', $event->reason);
        $this->assertSame('D.0.3a', $event->previousPhase);
        $this->assertSame('D.0.1', $event->fallbackPhase);
    }

    // =========================================================================
    // Observational Events
    // =========================================================================

    public function test_divergence_observed_is_non_authoritative(): void
    {
        $event = new DivergenceObserved(
            electionId: 'election_001',
            voterIdentifier: hash('sha256', 'voter_001'),
            divergenceCategory: 'constitutional_structural',
            constitutionalOutcome: 'DENIED',
            legacyOutcome: 'ALLOWED',
            evidenceEnvelopeHash: hash('sha256', 'envelope'),
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame('constitutional_structural', $event->divergenceCategory);
        // Observational events carry string outcomes, not LegitimacyOutcome enums
        // — they observe, they don't derive
        $this->assertIsString($event->constitutionalOutcome);
        $this->assertIsString($event->legacyOutcome);
    }

    public function test_observation_recorded_has_no_authority_semantics(): void
    {
        $event = new ObservationRecorded(
            overlayIdentifier: 'device_anomaly',
            finding: 'CONTEXT_STABLE',
            evidenceContext: ['device_hash' => 'abc123'],
            electionId: 'election_001',
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame('device_anomaly', $event->overlayIdentifier);
        $this->assertSame('CONTEXT_STABLE', $event->finding);

        // Must NOT contain authority vocabulary
        $this->assertStringNotContainsStringIgnoringCase('allow', $event->finding);
        $this->assertStringNotContainsStringIgnoringCase('deny', $event->finding);
        $this->assertStringNotContainsStringIgnoringCase('grant', $event->finding);
        $this->assertStringNotContainsStringIgnoringCase('block', $event->finding);
    }

    public function test_observational_events_have_no_laravel_dependencies(): void
    {
        foreach ([DivergenceObserved::class, ObservationRecorded::class] as $class) {
            $reflection = new \ReflectionClass($class);
            $contents = file_get_contents($reflection->getFileName());

            $this->assertStringNotContainsString('use Illuminate', $contents,
                "{$class} must have zero Laravel dependencies — pure domain only");
        }
    }
}
