<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Application\Fee\DTOs\RecordFeePaymentCommand;
use App\Contexts\Membership\Application\Fee\UseCases\RecordFeePayment;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEvent;
use App\Models\Organisation;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\User;
use App\Models\OrganisationUser;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OutboxIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private TenantId $tenantId;
    private MembershipType $membershipType;
    private int $transactionCounter = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::create([
            'id' => '11111111-1111-1111-1111-111111111111',
            'name' => 'Test Organisation',
            'slug' => 'test-organisation',
            'type' => 'tenant',
            'uses_full_membership' => true,
        ]);

        $this->membershipType = MembershipType::create([
            'organisation_id' => $this->organisation->id,
            'name' => 'Standard Member',
            'slug' => 'standard-member',
            'description' => 'Standard membership type',
            'price' => '50.00',
        ]);

        $this->tenantId = TenantId::fromOrganisationId($this->organisation->id);
    }

    private function createMember(): Member
    {
        return Member::factory()->create([
            'organisation_id' => $this->organisation->id,
            'membership_type_id' => $this->membershipType->id,
            'status' => 'active',
            'fees_status' => 'unpaid',
        ]);
    }

    private function uniqueTransactionReference(): string
    {
        return 'TXN-TEST-' . (++$this->transactionCounter) . '-' . now()->timestamp;
    }

    /** @test */
    public function payment_creates_outbox_event(): void
    {
        $member = $this->createMember();
        $memberId = MemberId::fromString($member->id);
        $membershipTypeId = MembershipTypeId::fromString($this->membershipType->id);

        // Create a fee via the domain
        $fee = \App\Contexts\Membership\Domain\Fee\Fee::create(
            $memberId,
            $membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // Save to database
        app(\App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface::class)
            ->save($fee, $this->tenantId);

        // Record payment
        $command = new RecordFeePaymentCommand(
            feeId: $fee->getId(),
            tenantId: $this->tenantId,
            paymentMethod: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: $this->uniqueTransactionReference(),
            recordedByUserId: 'user-123'
        );

        app(RecordFeePayment::class)->execute($command);

        // Verify outbox event was created
        $this->assertDatabaseHas('outbox_events', [
            'event_type' => 'FeePaid',
            'aggregate_type' => 'Fee',
            'status' => 'pending',
            'organisation_id' => $this->organisation->id,
        ]);
    }

    /** @test */
    public function outbox_event_persists_payload(): void
    {
        $member = $this->createMember();
        $memberId = MemberId::fromString($member->id);
        $membershipTypeId = MembershipTypeId::fromString($this->membershipType->id);

        $fee = \App\Contexts\Membership\Domain\Fee\Fee::create(
            $memberId,
            $membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        app(\App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface::class)
            ->save($fee, $this->tenantId);

        $command = new RecordFeePaymentCommand(
            feeId: $fee->getId(),
            tenantId: $this->tenantId,
            paymentMethod: 'card',
            paidAt: new DateTimeImmutable(),
            transactionReference: $this->uniqueTransactionReference(),
            recordedByUserId: 'user-456'
        );

        app(RecordFeePayment::class)->execute($command);

        $event = OutboxEvent::where('event_type', 'FeePaid')
            ->where('organisation_id', $this->organisation->id)
            ->first();

        $this->assertNotNull($event);
        $this->assertNotNull($event->payload);
        $this->assertEquals('FeePaid', $event->event_type);
        $this->assertEquals('pending', $event->status);
        $this->assertEquals(0, $event->attempts);
    }

    /** @test */
    public function outbox_event_can_be_marked_processed(): void
    {
        $event = OutboxEvent::create([
            'event_id' => Str::uuid()->toString(),
            'organisation_id' => $this->organisation->id,
            'aggregate_type' => 'Fee',
            'aggregate_id' => Str::uuid()->toString(),
            'event_type' => 'FeePaid',
            'payload' => ['test' => 'data'],
            'status' => 'pending',
            'available_at' => now(),
        ]);

        $event->markProcessed();

        $this->assertEquals('completed', $event->status);
        $this->assertNotNull($event->processed_at);
    }

    /** @test */
    public function outbox_event_can_be_rescheduled(): void
    {
        $event = OutboxEvent::create([
            'event_id' => Str::uuid()->toString(),
            'organisation_id' => $this->organisation->id,
            'aggregate_type' => 'Fee',
            'aggregate_id' => Str::uuid()->toString(),
            'event_type' => 'FeePaid',
            'payload' => ['test' => 'data'],
            'status' => 'pending',
            'available_at' => now(),
            'attempts' => 1,
        ]);

        $futureTime = now()->addMinutes(5);
        $event->incrementAttempts();
        $event->reschedule($futureTime);

        $this->assertEquals('pending', $event->status);
        $this->assertEquals(2, $event->attempts);
        $this->assertTrue($event->available_at->greaterThan(now()));
    }

    /** @test */
    public function waived_fee_creates_outbox_event(): void
    {
        $member = $this->createMember();
        $memberId = MemberId::fromString($member->id);
        $membershipTypeId = MembershipTypeId::fromString($this->membershipType->id);

        $fee = \App\Contexts\Membership\Domain\Fee\Fee::create(
            $memberId,
            $membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        app(\App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface::class)
            ->save($fee, $this->tenantId);

        $command = new \App\Contexts\Membership\Application\Fee\DTOs\WaiveFeeCommand(
            feeId: $fee->getId(),
            tenantId: $this->tenantId,
            reason: 'Hardship exemption',
            waivedByUserId: 'user-789'
        );

        app(\App\Contexts\Membership\Application\Fee\UseCases\WaiveFee::class)
            ->execute($command);

        // Verify outbox event for waiver
        $this->assertDatabaseHas('outbox_events', [
            'event_type' => 'FeeWaived',
            'aggregate_type' => 'Fee',
            'status' => 'pending',
            'organisation_id' => $this->organisation->id,
        ]);
    }
}
