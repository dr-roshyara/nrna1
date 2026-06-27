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

    public function processEvent(OutboxEvent $event): void
    {
        try {
            // Rehydrate domain event from stored payload
            $domainEvent = $this->hydrateDomainEvent($event);
            $integrationEvent = $this->hydrateIntegrationEvent($event);

            // Dispatch domain event (class-based listener binding)
            Event::dispatch($domainEvent);

            // Also dispatch integration event for cross-context listeners
            event($integrationEvent);

            // Mark as processed only after successful dispatch
            $event->markProcessed();
        } catch (\Throwable $e) {
            // Store error in a static variable for debugging in tests
            static::$lastException = $e;

            $this->handleFailure($event, $e);
        }
    }

    public static ?\Throwable $lastException = null;

    private function handleFailure(OutboxEvent $event, \Throwable $e): void
    {
        // Store error for debugging (can be queried in tests)
        $errorData = [
            'event_id' => $event->event_id,
            'event_type' => $event->event_type,
            'aggregate_id' => $event->aggregate_id,
            'organisation_id' => $event->organisation_id,
            'attempts' => $event->attempts,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ];

        \Log::error('Outbox event processing failed', $errorData);

        // Also store in database for test access (temporary)
        try {
            \DB::table('outbox_processing_errors')->insert([
                'outbox_event_id' => $event->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'created_at' => now(),
            ]);
        } catch (\Throwable) {
            // Table might not exist or transaction is aborted
        }

        // Attempt to update outbox event in a fresh transaction
        // This handles the case where RefreshDatabase test harness aborts the transaction
        try {
            DB::transaction(function () use ($event) {
                $event->incrementAttempts();

                if ($event->attempts >= self::MAX_ATTEMPTS) {
                    $event->markFailed();
                } else {
                    $event->reschedule(now()->addMinutes(self::RETRY_DELAY_MINUTES));
                }
            });
        } catch (\Throwable $updateError) {
            // If we can't update the outbox event itself, at least log it
            \Log::error('Failed to update outbox event retry status', [
                'outbox_event_id' => $event->id,
                'error' => $updateError->getMessage(),
            ]);
        }
    }

    private function hydrateDomainEvent(OutboxEvent $event): object
    {
        $payload = is_string($event->payload) ? json_decode($event->payload, true) : $event->payload;

        return match($event->event_type) {
            'FeePaid' => $this->hydrateFeePaid($payload),
            default => throw new \RuntimeException("Unknown event type: {$event->event_type}"),
        };
    }

    private function hydrateIntegrationEvent(OutboxEvent $event): object
    {
        $payload = is_string($event->payload) ? json_decode($event->payload, true) : $event->payload;

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

    private function hydrateFeePaid(array $payload): object
    {
        $feeIdClass = 'App\Contexts\Membership\Domain\Fee\FeeId';
        $memberIdClass = 'App\Contexts\Membership\Domain\Member\MemberId';
        $tenantIdClass = 'App\Contexts\Shared\Domain\ValueObjects\TenantId';

        return new \App\Contexts\Membership\Domain\Fee\Events\FeePaid(
            feeId: $feeIdClass::fromString($payload['feeId']),
            memberId: $memberIdClass::fromString($payload['memberId']),
            tenantId: $tenantIdClass::fromString($payload['tenantId']),
            amount: $payload['amount'],
            paymentMethod: $payload['paymentMethod'],
            paidAt: new \DateTimeImmutable($payload['paidAt']),
            transactionReference: $payload['transactionReference'] ?? null,
            recordedByUserId: $payload['recordedByUserId'] ?? null,
            currency: $payload['currency'] ?? 'EUR',
        );
    }

}
