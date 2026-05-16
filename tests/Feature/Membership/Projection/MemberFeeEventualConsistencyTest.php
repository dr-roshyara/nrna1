<?php

declare(strict_types=1);

namespace Tests\Feature\Membership\Projection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;

/**
 * Validate async outbox propagation correctness.
 *
 * Invariant: consistency is eventual, not transactional
 */
final class MemberFeeEventualConsistencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_fee_payment_becomes_eventually_consistent(): void
    {
        $scenario = \Tests\Support\Scenario\Scenario::create()
            ->bootstrapMembership()
            ->createFee();

        $member = $scenario->member;
        $fee = $scenario->fee;
        $tenantId = $scenario->tenantId;

        // Before payment: member is unpaid
        $this->assertEquals('unpaid', $member->fee_state);

        // Mark fee as paid (generates domain event)
        $paidAtTime = new \DateTimeImmutable();
        $payment = new \App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails(
            method: 'bank_transfer',
            paidAt: $paidAtTime,
            transactionReference: 'TX-EVENTUAL-001',
            recordedByUserId: 'test-user'
        );

        $fee->markAsPaid($payment);
        $event = $fee->pullEvents()[0];

        // Create outbox event (simulates persisted event)
        $outboxEventId = \Ramsey\Uuid\Uuid::uuid4()->toString();
        OutboxEvent::create([
            'id' => $outboxEventId,
            'event_id' => $outboxEventId,
            'organisation_id' => $tenantId->value(),
            'aggregate_type' => 'Fee',
            'aggregate_id' => $fee->getId()->value(),
            'event_type' => 'FeePaid',
            'payload' => json_encode([
                'feeId' => $event->getFeeId()->value(),
                'memberId' => $event->getMemberId()->value(),
                'tenantId' => $event->getTenantId()->value(),
                'amount' => $event->getAmount(),
                'paymentMethod' => $event->getPaymentMethod(),
                'paidAt' => $event->getPaidAt()->format('Y-m-d H:i:s'),
                'transactionReference' => $event->getTransactionReference(),
                'recordedByUserId' => $event->getRecordedByUserId(),
                'currency' => $event->getCurrency(),
            ]),
            'status' => 'pending',
            'available_at' => now(),
        ]);

        // Immediately after: member still unpaid (before outbox processing)
        $member->refresh();
        $this->assertEquals('unpaid', $member->fee_state);

        // Run outbox processor
        $this->runOutboxProcessor();

        // After processing: member is eventually consistent (paid)
        $member->refresh();
        $this->assertEquals('paid', $member->fee_state);
    }
}
