<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Support\Factories\FeeTestFactory;

/**
 * OutboxIntegrationTest — validates Phase 4A (Outbox durability system)
 *
 * This test suite verifies the event persistence and retry mechanism
 * WITHOUT testing domain logic. Domain logic is tested in unit tests.
 *
 * KEY PRINCIPLE: All setup goes through use cases ONLY.
 * No manual repository saves. No domain object creation in tests.
 */
class OutboxIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable async execution in tests to validate synchronous flow only
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

        // CRITICAL: Hard clean outbox for isolation
        // Even with RefreshDatabase, explicit cleanup prevents cross-test leakage
        OutboxEvent::query()->delete();
    }

    /** @test */
    public function payment_creates_outbox_event(): void
    {
        // GOLD STANDARD: Use factory (which uses RecordFeePayment use case)
        // This exercises the entire domain flow, not just DB state
        FeeTestFactory::createPaidFee([
            'tenantId' => $this->tenantId,
            'organisation' => $this->organisation,
        ]);

        // Assert: Outbox event was created with correct shape
        $this->assertDatabaseHas('outbox_events', [
            'event_type' => 'FeePaid',
            'aggregate_type' => 'Fee',
            'status' => 'pending',
            'organisation_id' => $this->organisation->id,
        ]);

        // Assert: Event has properly serialized payload
        $event = OutboxEvent::where('event_type', 'FeePaid')->first();
        $this->assertNotNull($event);
        $this->assertEquals(0, $event->attempts);
    }

    /** @test */
    public function outbox_event_persists_payload(): void
    {
        // Create a paid fee via use case
        FeeTestFactory::createPaidFee([
            'tenantId' => $this->tenantId,
            'organisation' => $this->organisation,
            'paymentMethod' => 'card',
        ]);

        // Assert: Payload contains all required event data
        $event = OutboxEvent::where('event_type', 'FeePaid')->first();

        $this->assertNotNull($event->payload);
        $this->assertEquals('FeePaid', $event->event_type);
        $this->assertEquals('pending', $event->status);
        $this->assertEquals(0, $event->attempts);

        // Assert: Payload structure (serialized domain event)
        $payload = is_string($event->payload) ? json_decode($event->payload, true) : $event->payload;
        $this->assertArrayHasKey('feeId', $payload);
        $this->assertArrayHasKey('amount', $payload);
        $this->assertArrayHasKey('paymentMethod', $payload);
        $this->assertArrayHasKey('paidAt', $payload);
    }

    /** @test */
    public function outbox_event_can_be_marked_processed(): void
    {
        // Arrange: Create a raw outbox event (testing OutboxEvent model, not domain flow)
        $event = OutboxEvent::create([
            'id' => (string) Str::uuid(),
            'event_id' => (string) Str::uuid(),
            'organisation_id' => $this->organisation->id,
            'aggregate_type' => 'Fee',
            'aggregate_id' => (string) Str::uuid(),
            'event_type' => 'FeePaid',
            'payload' => json_encode(['test' => 'data']),
            'status' => 'pending',
            'available_at' => now(),
        ]);

        // Act: Mark as processed
        $event->markProcessed();

        // Assert: State transitions correctly
        $this->assertEquals('completed', $event->status);
        $this->assertNotNull($event->processed_at);
    }

    /** @test */
    public function outbox_event_can_be_rescheduled(): void
    {
        // Arrange: Create a raw outbox event
        $event = OutboxEvent::create([
            'id' => (string) Str::uuid(),
            'event_id' => (string) Str::uuid(),
            'organisation_id' => $this->organisation->id,
            'aggregate_type' => 'Fee',
            'aggregate_id' => (string) Str::uuid(),
            'event_type' => 'FeePaid',
            'payload' => json_encode(['test' => 'data']),
            'status' => 'pending',
            'available_at' => now(),
            'attempts' => 1,
        ]);

        // Act: Increment and reschedule
        $event->incrementAttempts();
        $event->reschedule(now()->addMinutes(5));

        // Assert: Retry state updated correctly
        $this->assertEquals('pending', $event->status);
        $this->assertEquals(2, $event->attempts);
        $this->assertTrue($event->available_at->greaterThan(now()));
    }

    /** @test */
    public function waived_fee_creates_outbox_event(): void
    {
        // GOLD STANDARD: Use factory (which uses WaiveFee use case)
        FeeTestFactory::createWaivedFee([
            'tenantId' => $this->tenantId,
            'organisation' => $this->organisation,
            'reason' => 'Hardship exemption',
        ]);

        // Assert: Outbox event was created for fee waiver
        $this->assertDatabaseHas('outbox_events', [
            'event_type' => 'FeeWaived',
            'aggregate_type' => 'Fee',
            'status' => 'pending',
            'organisation_id' => $this->organisation->id,
        ]);

        $event = OutboxEvent::where('event_type', 'FeeWaived')->first();
        $this->assertNotNull($event);
        $this->assertEquals(0, $event->attempts);
    }
}
