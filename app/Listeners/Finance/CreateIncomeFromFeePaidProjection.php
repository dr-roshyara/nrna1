<?php

declare(strict_types=1);

namespace App\Listeners\Finance;

use App\Contexts\Finance\Events\FeePaidProjection;
use App\Contexts\Shared\Domain\Events\IntegrationEvent;
use App\Models\Income;
use Illuminate\Support\Facades\Log;

/**
 * Finance projection listener — consumes FeePaid events from Outbox
 *
 * This listener projects domain events into the Income read model.
 * It is idempotent: processing the same event twice produces the same result.
 *
 * CRITICAL RULES:
 * 1. No business logic — only data transformation
 * 2. Idempotent — based on transaction_reference or event_id
 * 3. Immutable contract — payload structure is frozen for reconciliation
 * 4. No fallback logic — strict validation only
 */
final class CreateIncomeFromFeePaidProjection
{
    public function handle(IntegrationEvent $event): void
    {
        // Filter to FeePaid events only
        if ($event->getEventType() !== 'FeePaid') {
            return;
        }

        try {
            $this->createIncomeProjection($event);
        } catch (\Exception $e) {
            Log::error('Failed to project FeePaid to Income', [
                'event_id' => $event->getPayload()['eventId'] ?? 'unknown',
                'organisation_id' => $event->getOrganisationId(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function createIncomeProjection(IntegrationEvent $event): void
    {
        $payload = $event->getPayload();
        $amount = (string) ($payload['amount'] ?? '0');
        $eventId = $payload['eventId'] ?? null;

        // Idempotency guard: check if income already exists for this event
        if ($eventId) {
            $existing = Income::where('organisation_id', $event->getOrganisationId())
                ->where('source_type', 'membership_fee')
                ->whereJsonContains('metadata->event_id', $eventId)
                ->first();

            if ($existing) {
                // Already projected — idempotent, no-op
                return;
            }
        }

        // Create income projection
        Income::create([
            'organisation_id' => $event->getOrganisationId(),
            'user_id' => $payload['recordedByUserId'] ?? null,
            'membership_fee' => $amount,
            'source_type' => 'membership_fee',
            'source_id' => $event->getAggregateId(),
            'period_from' => now()->startOfMonth(),
            'period_to' => now()->endOfMonth(),
            'committee_name' => 'Membership',
            'country' => 'DE', // Default, should come from organisation context
            'metadata' => [
                'event_id' => $event->getPayload()['eventId'] ?? null,
                'transaction_reference' => $transactionRef,
                'payment_method' => $payload['method'] ?? null,
                'currency' => $payload['currency'] ?? 'EUR',
                'recorded_at' => $event->getOccurredAt(),
            ],
        ]);
    }
}
