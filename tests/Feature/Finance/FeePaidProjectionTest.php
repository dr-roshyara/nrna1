<?php

declare(strict_types=1);

namespace Tests\Feature\Finance;

use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Models\Income;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\Support\Factories\FeeTestFactory;
use Tests\TestCase;

/**
 * FeePaidProjectionTest — validates Phase 4B (Finance projection from events)
 *
 * This test suite verifies event-driven CQRS:
 * Domain event (FeePaid) → Outbox → Projection → Income read model
 *
 * KEY PRINCIPLE: Listener reads ONLY from event payload, not from Membership database
 */
class FeePaidProjectionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        // Prevent async execution
        Queue::fake();
        Event::fake();

        // Create test organisation
        $this->organisation = Organisation::create([
            'id' => '11111111-1111-1111-1111-111111111111',
            'name' => 'Test Organisation',
            'slug' => 'test-organisation',
            'type' => 'tenant',
            'uses_full_membership' => true,
        ]);

        $this->tenantId = TenantId::fromOrganisationId($this->organisation->id);

        // Hard clean projections for isolation
        Income::query()->delete();
    }

    /** @test */
    public function fee_paid_creates_income_record(): void
    {
        // Arrange: Create paid fee (which creates Outbox event)
        FeeTestFactory::createPaidFee([
            'tenantId' => $this->tenantId,
            'organisation' => $this->organisation,
            'paymentMethod' => 'bank_transfer',
        ]);

        // Act: Dispatch the outbox event to the listener
        $outboxEvent = \App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent::query()
            ->where('event_type', 'FeePaid')
            ->where('organisation_id', $this->organisation->id)
            ->first();

        $this->assertNotNull($outboxEvent, 'FeePaid event not found in Outbox');

        // Manually trigger the listener (simulating OutboxEventProcessor)
        $integrationEvent = new \App\Contexts\Shared\Domain\Events\IntegrationEvent(
            eventId: $outboxEvent->event_id,
            eventType: 'FeePaid',
            aggregateType: 'Fee',
            aggregateId: $outboxEvent->aggregate_id,
            organisationId: $outboxEvent->organisation_id,
            payload: is_string($outboxEvent->payload) ? json_decode($outboxEvent->payload, true) : $outboxEvent->payload,
            occurredAt: $outboxEvent->created_at,
        );

        app(\App\Listeners\Finance\CreateIncomeFromFeePaidProjection::class)
            ->handle($integrationEvent);

        // Assert: Income record created
        $this->assertDatabaseHas('incomes', [
            'organisation_id' => $this->organisation->id,
            'source_type' => 'membership_fee',
            'source_id' => $outboxEvent->aggregate_id,
        ]);

        $income = Income::where('source_id', $outboxEvent->aggregate_id)->first();
        $this->assertNotNull($income);
        $this->assertEquals(100.00, (float) $income->membership_fee);
    }

    /** @test */
    public function income_projection_is_idempotent(): void
    {
        // Arrange: Create paid fee
        FeeTestFactory::createPaidFee([
            'tenantId' => $this->tenantId,
            'organisation' => $this->organisation,
        ]);

        $outboxEvent = \App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent::query()
            ->where('event_type', 'FeePaid')
            ->first();

        $integrationEvent = new \App\Contexts\Shared\Domain\Events\IntegrationEvent(
            eventId: $outboxEvent->event_id,
            eventType: 'FeePaid',
            aggregateType: 'Fee',
            aggregateId: $outboxEvent->aggregate_id,
            organisationId: $outboxEvent->organisation_id,
            payload: is_string($outboxEvent->payload) ? json_decode($outboxEvent->payload, true) : $outboxEvent->payload,
            occurredAt: $outboxEvent->created_at,
        );

        // Act: Process event TWICE (simulating replay)
        app(\App\Listeners\Finance\CreateIncomeFromFeePaidProjection::class)->handle($integrationEvent);
        $firstCount = Income::count();

        app(\App\Listeners\Finance\CreateIncomeFromFeePaidProjection::class)->handle($integrationEvent);
        $secondCount = Income::count();

        // Assert: No duplicate created (idempotent)
        $this->assertEquals($firstCount, $secondCount);
        $this->assertEquals(1, $secondCount, 'Idempotency failed: duplicate Income created');
    }

    /** @test */
    public function income_preserves_event_audit_trail_in_metadata(): void
    {
        // Arrange: Create paid fee with specific transaction reference
        $txnRef = 'TXN-' . (string) Str::uuid();
        FeeTestFactory::createPaidFee([
            'tenantId' => $this->tenantId,
            'organisation' => $this->organisation,
            'paymentMethod' => 'card',
            'transactionReference' => $txnRef,
        ]);

        $outboxEvent = \App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent::query()
            ->where('event_type', 'FeePaid')
            ->first();

        $integrationEvent = new \App\Contexts\Shared\Domain\Events\IntegrationEvent(
            eventId: $outboxEvent->event_id,
            eventType: 'FeePaid',
            aggregateType: 'Fee',
            aggregateId: $outboxEvent->aggregate_id,
            organisationId: $outboxEvent->organisation_id,
            payload: is_string($outboxEvent->payload) ? json_decode($outboxEvent->payload, true) : $outboxEvent->payload,
            occurredAt: $outboxEvent->created_at,
        );

        // Act: Project to Income
        app(\App\Listeners\Finance\CreateIncomeFromFeePaidProjection::class)->handle($integrationEvent);

        // Assert: Metadata contains all event facts
        $income = Income::where('source_id', $outboxEvent->aggregate_id)->first();
        $this->assertNotNull($income);

        $metadata = json_decode($income->metadata, true);
        $this->assertArrayHasKey('event_id', $metadata);
        $this->assertArrayHasKey('transaction_reference', $metadata);
        $this->assertArrayHasKey('payment_method', $metadata);
        $this->assertArrayHasKey('paidAt', $metadata);
        $this->assertArrayHasKey('currency', $metadata);

        $this->assertEquals($txnRef, $metadata['transaction_reference']);
        $this->assertEquals('card', $metadata['payment_method']);
    }

    /** @test */
    public function income_uses_payment_received_date_for_period(): void
    {
        // Arrange: Create paid fee
        FeeTestFactory::createPaidFee([
            'tenantId' => $this->tenantId,
            'organisation' => $this->organisation,
        ]);

        $outboxEvent = \App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent::query()
            ->where('event_type', 'FeePaid')
            ->first();

        $integrationEvent = new \App\Contexts\Shared\Domain\Events\IntegrationEvent(
            eventId: $outboxEvent->event_id,
            eventType: 'FeePaid',
            aggregateType: 'Fee',
            aggregateId: $outboxEvent->aggregate_id,
            organisationId: $outboxEvent->organisation_id,
            payload: is_string($outboxEvent->payload) ? json_decode($outboxEvent->payload, true) : $outboxEvent->payload,
            occurredAt: $outboxEvent->created_at,
        );

        // Act: Project to Income
        app(\App\Listeners\Finance\CreateIncomeFromFeePaidProjection::class)->handle($integrationEvent);

        // Assert: period_from and period_to are current month (payment received date)
        $income = Income::where('source_id', $outboxEvent->aggregate_id)->first();
        $this->assertNotNull($income);

        $this->assertEquals(now()->startOfMonth()->toDateString(), $income->period_from->toDateString());
        $this->assertEquals(now()->endOfMonth()->toDateString(), $income->period_to->toDateString());
    }

    /** @test */
    public function non_fee_paid_events_are_ignored(): void
    {
        // Arrange: Create a non-FeePaid event (FeeWaived)
        FeeTestFactory::createWaivedFee([
            'tenantId' => $this->tenantId,
            'organisation' => $this->organisation,
        ]);

        $outboxEvent = \App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent::query()
            ->where('event_type', 'FeeWaived')
            ->first();

        $integrationEvent = new \App\Contexts\Shared\Domain\Events\IntegrationEvent(
            eventId: $outboxEvent->event_id,
            eventType: 'FeeWaived',
            aggregateType: 'Fee',
            aggregateId: $outboxEvent->aggregate_id,
            organisationId: $outboxEvent->organisation_id,
            payload: is_string($outboxEvent->payload) ? json_decode($outboxEvent->payload, true) : $outboxEvent->payload,
            occurredAt: $outboxEvent->created_at,
        );

        // Act: Try to project FeeWaived event
        app(\App\Listeners\Finance\CreateIncomeFromFeePaidProjection::class)->handle($integrationEvent);

        // Assert: No Income record created (event filtered out)
        $this->assertDatabaseMissing('incomes', [
            'source_id' => $outboxEvent->aggregate_id,
        ]);
    }
}
