<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Outbox;

use App\Contexts\Contestation\Application\ChallengeResolvedIntegration;
use App\Contexts\Contestation\Application\Port\ChallengeEventOutbox;
use App\Contexts\Contestation\Domain\Events\ChallengeAdjudicated;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Services\TenantContext;
use Illuminate\Support\Str;
use LogicException;

/**
 * Infrastructure adapter for {@see ChallengeEventOutbox}: translates, serializes, and
 * enqueues Contestation's published Integration Events to the shared `outbox_events`
 * (the relay publishes later). Explicit per-event mapping (no reflection). Transport
 * `event_id` is adapter-assigned; `organisation_id` via `TenantContext::require()`.
 *
 * `ChallengeResolved` is enriched with `resolution` — supplied by the Application through
 * {@see ChallengeResolvedIntegration} (F-2). The domain event stays minimal; only the
 * published payload carries `resolution`. Runs inside the inbox transaction (ADR-T1).
 */
final class ChallengeOutboxAdapter implements ChallengeEventOutbox
{
    public function enqueue(object ...$events): void
    {
        foreach ($events as $event) {
            match (true) {
                $event instanceof ChallengeAdjudicated => $this->writeAdjudicated($event),
                $event instanceof ChallengeResolvedIntegration => $this->writeResolved($event),
                default => throw new LogicException('No outbox mapping for event '.$event::class),
            };
        }
    }

    private function writeAdjudicated(ChallengeAdjudicated $event): void
    {
        (new OutboxEvent([
            'event_id' => (string) Str::uuid(),
            'organisation_id' => TenantContext::require(),
            'aggregate_type' => 'Challenge',
            'aggregate_id' => $event->challengeId->toString(),
            'event_type' => 'ChallengeAdjudicated',
            'payload' => [
                'schema_version' => 1,
                'challengeId' => $event->challengeId->toString(),
                'determinationId' => $event->determinationId->toString(),
                'occurredAt' => $event->occurredAt->format(DATE_ATOM),
            ],
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
        ]))->save();
    }

    private function writeResolved(ChallengeResolvedIntegration $integration): void
    {
        $event = $integration->event;

        (new OutboxEvent([
            'event_id' => (string) Str::uuid(),
            'organisation_id' => TenantContext::require(),
            'aggregate_type' => 'Challenge',
            'aggregate_id' => $event->challengeId->toString(),
            'event_type' => 'ChallengeResolved',
            'payload' => [
                'schema_version' => 1,
                'challengeId' => $event->challengeId->toString(),
                'determinationId' => $event->determinationId->toString(),
                'resolution' => $integration->resolution->value,   // integration-only enrichment (F-2)
                'occurredAt' => $event->occurredAt->format(DATE_ATOM),
            ],
            'status' => 'pending',
            'attempts' => 0,
            'available_at' => now(),
        ]))->save();
    }
}
