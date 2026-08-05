<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Contestation;

use App\Contexts\Contestation\Domain\Events\ChallengeAdjudicated;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Contestation\Infrastructure\Outbox\ChallengeAdjudicatedHydrator;
use App\Contexts\Contestation\Infrastructure\Outbox\ChallengeResolvedHydrator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * PB-005 Step 5C (RED) — hydrators reconstruct the MINIMAL Contestation domain events from
 * the published payload (the relay re-emits them). The `resolution` field lives on the
 * published payload for downstream consumers but is NOT part of the domain event (F-2), so
 * the hydrator does not put it on the reconstructed event. Unsupported schema versions are
 * rejected, never silently hydrated (ER-07 — transport tested here, separately).
 */
final class ChallengeEventHydratorsTest extends TestCase
{
    public function test_adjudicated_hydrator_reconstructs_the_domain_event(): void
    {
        $hydrator = new ChallengeAdjudicatedHydrator();
        $this->assertSame('ChallengeAdjudicated', $hydrator->eventType());

        $event = $hydrator->hydrate([
            'schema_version' => 1,
            'challengeId' => 'ch-1',
            'determinationId' => 'det-1',
            'occurredAt' => '2026-07-09T10:00:00+00:00',
        ]);

        $this->assertInstanceOf(ChallengeAdjudicated::class, $event);
        $this->assertSame('ch-1', $event->challengeId->toString());
        $this->assertSame('det-1', $event->determinationId->toString());
    }

    public function test_resolved_hydrator_reconstructs_the_minimal_domain_event_ignoring_resolution(): void
    {
        $hydrator = new ChallengeResolvedHydrator();
        $this->assertSame('ChallengeResolved', $hydrator->eventType());

        $event = $hydrator->hydrate([
            'schema_version' => 1,
            'challengeId' => 'ch-1',
            'determinationId' => 'det-1',
            'resolution' => 'upheld', // integration-only enrichment — must NOT appear on the domain event
            'occurredAt' => '2026-07-09T10:05:00+00:00',
        ]);

        $this->assertInstanceOf(ChallengeResolved::class, $event);
        $this->assertSame('ch-1', $event->challengeId->toString());
        $this->assertSame('det-1', $event->determinationId->toString());
        $this->assertFalse(property_exists($event, 'resolution'), 'the domain event stays minimal (F-2)');
    }

    public function test_unsupported_schema_version_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new ChallengeResolvedHydrator())->hydrate([
            'schema_version' => 99,
            'challengeId' => 'ch-1',
            'determinationId' => 'det-1',
            'resolution' => 'upheld',
            'occurredAt' => '2026-07-09T10:05:00+00:00',
        ]);
    }
}
