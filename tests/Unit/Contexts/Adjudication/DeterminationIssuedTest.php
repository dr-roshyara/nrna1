<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication;

use App\Contexts\Adjudication\Domain\DomainEvent;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use DateTimeImmutable;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

final class DeterminationIssuedTest extends TestCase
{
    public function test_event_implements_domain_event(): void
    {
        $this->assertInstanceOf(DomainEvent::class, $this->createEvent());
    }

    public function test_event_is_readonly(): void
    {
        $this->assertTrue((new ReflectionClass(DeterminationIssued::class))->isReadOnly());
    }

    public function test_event_carries_all_expected_properties(): void
    {
        $event = $this->createEvent();

        $this->assertSame('det-123', $event->determinationId->toString());
        $this->assertSame('ch-456', $event->challengeRef->toString());
        $this->assertSame(DeterminationOutcome::Upheld, $event->outcome);
        $this->assertSame(Legitimacy::Legitimate, $event->legitimacy);
        $this->assertSame('Tally dispute upheld.', $event->reason->toString());
        $this->assertSame('ev-789', $event->evidenceEnvelopeRef->toString());
        $this->assertSame('ARB', $event->issuedByAuthority->toString());
        $this->assertSame('National', $event->jurisdiction->toString());
        $this->assertInstanceOf(DateTimeImmutable::class, $event->occurredAt);
    }

    private function createEvent(): DeterminationIssued
    {
        return new DeterminationIssued(
            DeterminationId::fromString('det-123'),
            ChallengeRef::fromString('ch-456'),
            DeterminationOutcome::Upheld,
            Legitimacy::Legitimate,
            Reason::fromString('Tally dispute upheld.'),
            EvidenceEnvelopeRef::fromString('ev-789'),
            IssuedByAuthority::fromString('ARB'),
            Jurisdiction::fromString('National'),
            null,   // contestedOutcome (schema v2) — not exercised by this VO/shape test
            null,   // evidenceSet (schema v3) — not exercised by this VO/shape test
            new DateTimeImmutable('2026-06-27T10:00:00+00:00'),
        );
    }
}
