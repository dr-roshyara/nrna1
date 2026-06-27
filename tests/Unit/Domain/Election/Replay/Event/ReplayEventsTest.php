<?php

namespace Tests\Unit\Domain\Election\Replay\Event;

use App\Domain\Election\Replay\Event\ReplayCertificationIssued;
use App\Domain\Election\Replay\Event\ReplayDivergenceDetected;
use App\Domain\Election\Replay\Event\ReplaySessionOpened;
use PHPUnit\Framework\TestCase;

class ReplayEventsTest extends TestCase
{
    public function test_replay_session_opened_immutable(): void
    {
        $event = new ReplaySessionOpened(
            sessionId: hash('sha256', 'session_001'),
            envelopeHash: hash('sha256', 'envelope'),
            electionIdentifier: 'election_001',
            compatibilityVersion: '1.0',
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame('election_001', $event->electionIdentifier);
        $this->assertSame('1.0', $event->compatibilityVersion);
        $this->assertTrue((new \ReflectionClass($event))->isReadOnly(),
            'Replay events must be readonly — immutable after emission');
    }

    public function test_replay_certification_issued_records_matched_outcome(): void
    {
        $event = new ReplayCertificationIssued(
            sessionId: hash('sha256', 'session_001'),
            envelopeHash: hash('sha256', 'envelope'),
            outcome: 'SUFFICIENT_EVIDENCE',
            certificationHash: hash('sha256', 'cert'),
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame('SUFFICIENT_EVIDENCE', $event->outcome);
    }

    public function test_replay_divergence_detected_records_mismatch(): void
    {
        $event = new ReplayDivergenceDetected(
            sessionId: hash('sha256', 'session_001'),
            envelopeHash: hash('sha256', 'envelope'),
            expectedOutcome: 'SUFFICIENT_EVIDENCE',
            actualOutcome: 'INSUFFICIENT_EVIDENCE',
            occurredAt: new \DateTimeImmutable('2026-05-29 12:00:00'),
        );

        $this->assertSame('SUFFICIENT_EVIDENCE', $event->expectedOutcome);
        $this->assertSame('INSUFFICIENT_EVIDENCE', $event->actualOutcome);
        $this->assertNotSame($event->expectedOutcome, $event->actualOutcome,
            'Divergence detected requires actual outcome differs from expected');
    }

    public function test_replay_events_have_no_laravel_dependencies(): void
    {
        foreach ([ReplaySessionOpened::class, ReplayCertificationIssued::class, ReplayDivergenceDetected::class] as $class) {
            $reflection = new \ReflectionClass($class);
            $contents = file_get_contents($reflection->getFileName());

            $this->assertStringNotContainsString('use Illuminate', $contents,
                "{$class} must have zero Laravel dependencies — pure domain only");
        }
    }
}
