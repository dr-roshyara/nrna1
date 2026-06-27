<?php

declare(strict_types=1);

namespace App\Listeners\Finance;

use App\Contexts\Shared\Domain\Events\IntegrationEvent;
use App\Models\Income;

/**
 * Finance projection listener — consumes FeePaid events from Outbox
 *
 * CRITICAL RULES (Event-Driven CQRS):
 * 1. Event payload is source of truth — no cross-context database queries
 * 2. Idempotent via event_id (primary) + transaction_reference (secondary)
 * 3. Minimal transformation only — no enrichment, no business logic
 * 4. Let exceptions bubble up — Outbox processor handles retries
 * 5. All event facts preserved in metadata for audit trail
 */
final class CreateIncomeFromFeePaidProjection
{
    public function handle(IntegrationEvent $event): void
    {
        // Filter to FeePaid events only
        if ($event->getEventType() !== 'FeePaid') {
            return;
        }

        // Idempotency check: prevent duplicate Income records
        if (!$this->shouldProcess($event)) {
            return;
        }

        $payload = $event->getPayload();

        // Minimal transformation: event → read model (no enrichment)
        Income::create([
            'organisation_id' => $event->getOrganisationId(),
            'user_id' => $payload['recordedByUserId'] ?? null,
            'membership_fee' => (float) ($payload['amount'] ?? '0'),
            'source_type' => 'membership_fee',
            'source_id' => $payload['feeId'],

            // Period = when payment was received (now), not fee due date
            'period_from' => now()->startOfMonth(),
            'period_to' => now()->endOfMonth(),

            // Default values (no cross-context queries)
            'country' => 'DE',
            'committee_name' => 'Membership',

            // Audit trail: all event facts preserved
            'metadata' => json_encode([
                'event_id' => $event->getEventId(),
                'transaction_reference' => $payload['transactionReference'] ?? null,
                'payment_method' => $payload['paymentMethod'] ?? null,
                'paidAt' => $payload['paidAt'] ?? null,
                'currency' => $payload['currency'] ?? 'EUR',
            ]),
        ]);
    }

    private function shouldProcess(IntegrationEvent $event): bool
    {
        $eventId = $event->getEventId();
        $payload = $event->getPayload();
        $txnRef = $payload['transactionReference'] ?? null;

        // Primary idempotency: check by event_id
        if (Income::where('metadata->event_id', $eventId)->exists()) {
            return false;
        }

        // Secondary idempotency: check by transaction_reference (if present)
        if ($txnRef && Income::where('metadata->transaction_reference', $txnRef)->exists()) {
            return false;
        }

        return true;
    }
}
