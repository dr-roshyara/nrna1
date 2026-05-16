<?php

declare(strict_types=1);

namespace Tests\Unit\Fee;

use Tests\TestCase;
use Tests\Support\Scenario\Scenario;
use Tests\Support\Builders\FeeBuilder;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails;
use DateTimeImmutable;

final class FeePaymentDomainTest extends TestCase
{
    /**
     * INVARIANT: FeePaid event must contain complete context (feeId, memberId, tenantId)
     * This ensures event is self-sufficient for async replay without external inference.
     */
    public function test_fee_mark_as_paid_emits_fee_paid_event_with_complete_context(): void
    {
        // Arrange: Use Scenario for production-aligned setup
        $scenario = Scenario::create()
            ->bootstrapMembership()
            ->createFee();

        $fee = $scenario->fee;
        $memberId = $scenario->member->id;
        $tenantId = $scenario->tenantId;

        $payment = new PaymentDetails(
            method: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: 'TX-2026-0516-001',
            recordedByUserId: 'user-admin'
        );

        // Act
        $fee->markAsPaid($payment);

        // Assert: FeePaid is emitted
        $events = $fee->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(FeePaid::class, $events[0]);

        /** @var FeePaid $event */
        $event = $events[0];

        // Assert: complete context is present (not relying on external lookup)
        $this->assertEquals($memberId, $event->getMemberId()->value());
        $this->assertEquals($tenantId->value(), $event->getTenantId()->value());
        $this->assertEquals($fee->getId()->value(), $event->getFeeId()->value());

        // Assert: payment details captured
        $this->assertEquals('bank_transfer', $event->getPaymentMethod());
        $this->assertEquals('TX-2026-0516-001', $event->getTransactionReference());
        $this->assertNotNull($event->getPaidAt());
    }

    /**
     * INVARIANT: Fee cannot transition to PAID twice (idempotency guard)
     * Protects against double-payment from replay scenarios.
     */
    public function test_fee_cannot_be_marked_paid_twice(): void
    {
        // Arrange
        $scenario = Scenario::create()
            ->bootstrapMembership()
            ->createFee();

        $fee = $scenario->fee;

        $payment = new PaymentDetails(
            method: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: 'TX-001'
        );

        $fee->markAsPaid($payment);

        // Act & Assert: second payment throws
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Fee cannot be paid in its current state');

        $fee->markAsPaid($payment);
    }

    /**
     * INVARIANT: Event payload maintains semantic accuracy
     * No corruption or data loss during domain event recording.
     */
    public function test_fee_paid_event_preserves_all_payment_details(): void
    {
        // Arrange
        $scenario = Scenario::create()
            ->bootstrapMembership()
            ->createFee();

        $fee = $scenario->fee;

        $paidAtTime = new DateTimeImmutable('2026-05-16 14:30:00');
        $payment = new PaymentDetails(
            method: 'bank_transfer',
            paidAt: $paidAtTime,
            transactionReference: 'TX-2026-0516-XYZ',
            recordedByUserId: 'user-finance-team'
        );

        // Act
        $fee->markAsPaid($payment);
        $event = $fee->pullEvents()[0];

        // Assert: all payment details preserved exactly
        $this->assertEquals('bank_transfer', $event->getPaymentMethod());
        $this->assertEquals('TX-2026-0516-XYZ', $event->getTransactionReference());
        $this->assertEquals('user-finance-team', $event->getRecordedByUserId());
        $this->assertEquals($paidAtTime, $event->getPaidAt());
        $this->assertEquals('100.00', $event->getAmount()); // Default from builder
        $this->assertEquals('EUR', $event->getCurrency());
    }
}
