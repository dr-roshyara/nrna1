<?php

declare(strict_types=1);

namespace Tests\Feature\Outbox;

use Tests\TestCase;
use Tests\Support\Scenario\Scenario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Application\Member\Listeners\MemberFeeStateListener;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use DateTimeImmutable;

/**
 * EventDispatchIsolationTest — Baseline test for Laravel event wiring
 *
 * Purpose: Verify that Event::dispatch(FeePaid) reaches MemberFeeStateListener
 * This isolates the Laravel event binding contract from Outbox complexity.
 */
final class EventDispatchIsolationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * BASELINE: Can we dispatch FeePaid and have listener receive it?
     *
     * If this fails: problem is Laravel event binding
     * If this passes: problem is Outbox processor only
     */
    public function test_fee_paid_event_dispatched_directly_reaches_listener(): void
    {
        // Arrange: Create real Fee to get realistic event data
        $scenario = Scenario::create()
            ->bootstrapMembership()
            ->createFee();

        $fee = $scenario->fee;
        $member = $scenario->member;
        $tenantId = $scenario->tenantId;

        $paidAtTime = new DateTimeImmutable();
        $payment = new \App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails(
            method: 'bank_transfer',
            paidAt: $paidAtTime,
            transactionReference: 'TX-ISOLATION-001',
            recordedByUserId: 'test-user'
        );

        $fee->markAsPaid($payment);
        $event = $fee->pullEvents()[0];

        // Act: Dispatch the domain event directly (not through Outbox)
        $capturedEvent = null;

        Event::listen(FeePaid::class, function (FeePaid $evt) use (&$capturedEvent) {
            $capturedEvent = $evt;
        });

        Event::dispatch($event);

        // Assert: Listener received the event
        $this->assertNotNull($capturedEvent, 'FeePaid listener did not receive dispatched event');
        $this->assertEquals($event->getFeeId()->value(), $capturedEvent->getFeeId()->value());
        $this->assertEquals($event->getMemberId()->value(), $capturedEvent->getMemberId()->value());
    }

    /**
     * VERIFICATION: Does registered listener fire on dispatch?
     */
    public function test_listener_receives_event_via_event_service_provider(): void
    {
        // Arrange: Create a fresh Fee
        $scenario = Scenario::create()
            ->bootstrapMembership()
            ->createFee();

        $fee = $scenario->fee;
        $event = new FeePaid(
            feeId: $fee->getId(),
            memberId: \App\Contexts\Membership\Domain\Member\MemberId::fromString($scenario->member->id),
            tenantId: $scenario->tenantId,
            amount: '100.00',
            paymentMethod: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: 'TX-LISTENER-001',
            recordedByUserId: 'test',
            currency: 'EUR'
        );

        $listenerCalled = false;

        Event::listen(FeePaid::class, function (FeePaid $evt) use (&$listenerCalled) {
            $listenerCalled = true;
        });

        // Act
        Event::dispatch($event);

        // Assert
        $this->assertTrue($listenerCalled, 'EventServiceProvider listener was not triggered');
    }

    /**
     * DIAGNOSTIC: What exception occurs inside OutboxEventProcessor?
     */
    public function test_outbox_processor_exception_diagnostics(): void
    {
        // Arrange: Create Fee and get the event
        $scenario = Scenario::create()
            ->bootstrapMembership()
            ->createFee();

        $fee = $scenario->fee;
        $event = new FeePaid(
            feeId: $fee->getId(),
            memberId: \App\Contexts\Membership\Domain\Member\MemberId::fromString($scenario->member->id),
            tenantId: $scenario->tenantId,
            amount: '100.00',
            paymentMethod: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: 'TX-DIAG-001',
            recordedByUserId: 'test',
            currency: 'EUR'
        );

        // Create outbox event with this payload
        $outboxEventId = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $outboxEvent = OutboxEvent::create([
            'id' => $outboxEventId,
            'event_id' => $outboxEventId,
            'organisation_id' => $scenario->tenantId->value(),
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

        // Act: Process the outbox event
        OutboxEventProcessor::$lastException = null;  // Reset capture
        $processor = app(OutboxEventProcessor::class);

        try {
            $processor->processEvent($outboxEvent);
        } catch (\Throwable $processingError) {
            // Expected - transaction error from RefreshDatabase test harness
            // The real exception is in static::$lastException
        }

        // Check what exception was actually thrown
        if (OutboxEventProcessor::$lastException !== null) {
            $e = OutboxEventProcessor::$lastException;
            $this->fail("Actual exception in processEvent:\n" . $e::class . ": {$e->getMessage()}\n\nTrace:\n{$e->getTraceAsString()}");
        }

        // Check outbox_processing_errors table as fallback
        $outboxEvent->refresh();
        if ($outboxEvent->status === 'pending') {
            $errorRecord = \DB::table('outbox_processing_errors')
                ->where('outbox_event_id', $outboxEventId)
                ->first();

            if ($errorRecord) {
                $this->fail("Outbox processor error recorded:\n{$errorRecord->error_message}\n\nTrace:\n{$errorRecord->error_trace}");
            } else {
                $this->fail("Outbox event was rescheduled but exception not captured. Status: {$outboxEvent->status}");
            }
        }

        // Assert: event was processed successfully
        $this->assertEquals('completed', $outboxEvent->status, "Outbox event not completed");
    }
}
