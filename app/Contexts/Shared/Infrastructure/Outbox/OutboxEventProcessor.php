<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

final class OutboxEventProcessor
{
    private const MAX_ATTEMPTS = 5;
    private const RETRY_DELAY_MINUTES = 5;

    public function handle(): void
    {
        OutboxEvent::query()
            ->pending()
            ->where('available_at', '<=', now())
            ->orderBy('created_at')
            ->limit(100)
            ->get()
            ->each(fn(OutboxEvent $event) => $this->processEvent($event));
    }

    private function processEvent(OutboxEvent $event): void
    {
        try {
            DB::transaction(function () use ($event) {
                // Dispatch integration event to listeners (Finance context, etc.)
                // This triggers cross-context projections
                Event::dispatch('outbox.event', [$this->hydrate($event)]);

                // Mark as processed only after successful dispatch
                $event->markProcessed();
            });
        } catch (\Throwable $e) {
            $this->handleFailure($event, $e);
        }
    }

    private function handleFailure(OutboxEvent $event, \Throwable $e): void
    {
        \Log::error('Outbox event processing failed', [
            'event_id' => $event->event_id,
            'event_type' => $event->event_type,
            'aggregate_id' => $event->aggregate_id,
            'organisation_id' => $event->organisation_id,
            'attempts' => $event->attempts,
            'error' => $e->getMessage(),
        ]);

        $event->incrementAttempts();

        if ($event->attempts >= self::MAX_ATTEMPTS) {
            $event->markFailed();
        } else {
            $event->reschedule(now()->addMinutes(self::RETRY_DELAY_MINUTES));
        }
    }

    private function hydrate(OutboxEvent $event): object
    {
        // Reconstruct the domain event from payload
        // This will be expanded as more event types are added
        $eventClass = $this->resolveEventClass($event->event_type);
        $payload = is_string($event->payload) ? json_decode($event->payload, true) : $event->payload;

        // For now, return a generic integration event
        // In Phase 4B, this becomes a proper event hydration factory
        return new IntegrationEvent(
            eventId: $event->event_id,
            eventType: $event->event_type,
            aggregateType: $event->aggregate_type,
            aggregateId: $event->aggregate_id,
            organisationId: $event->organisation_id,
            payload: $payload,
            occurredAt: $event->created_at,
        );
    }

    private function resolveEventClass(string $eventType): string
    {
        // Map event types to their classes
        $mapping = [
            'FeePaid' => 'App\Contexts\Membership\Domain\Fee\Events\FeePaid',
            'FeeWaived' => 'App\Contexts\Membership\Domain\Fee\Events\FeeWaived',
        ];

        return $mapping[$eventType] ?? IntegrationEvent::class;
    }
}
