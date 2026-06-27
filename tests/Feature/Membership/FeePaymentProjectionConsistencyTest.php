<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

use Tests\TestCase;
use Tests\Support\DomainIdFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Domain\Member\ValueObjects\FeeState;
use App\Contexts\Membership\Application\Member\Listeners\MemberFeeStateListener;
use App\Contexts\Membership\Infrastructure\Models\MemberContextModel;
use DateTimeImmutable;

final class FeePaymentProjectionConsistencyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * INVARIANT: Member projection is updated when FeePaid event is received
     * Listener is event-driven only (no Fee aggregate reload required).
     */
    public function test_member_fee_state_is_updated_from_fee_paid_event(): void
    {
        // Arrange: create member with unpaid fee state
        $memberId = DomainIdFactory::member();
        $tenantId = DomainIdFactory::tenant();

        MemberContextModel::create([
            'id' => $memberId->value(),
            'organisation_id' => $tenantId->value(),
            'membership_type_id' => DomainIdFactory::membershipType()->value(),
            'status' => 'active',
            'fee_state' => 'unpaid',
            'personal_info' => json_encode([
                'fullName' => 'Test Member',
                'email' => 'test@example.com',
                'phone' => '+1234567890',
            ]),
        ]);

        // Create event signaling fee was paid
        $event = new FeePaid(
            feeId: DomainIdFactory::fee(),
            memberId: $memberId,
            tenantId: $tenantId,
            amount: '100.00',
            paymentMethod: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: 'TX-PROJ-001',
            recordedByUserId: 'user-proj-1'
        );

        $listener = app(MemberFeeStateListener::class);

        // Act: dispatch event to listener
        $listener->handle($event);

        // Assert: member's fee state updated to PAID
        $updated = MemberContextModel::find($memberId->value());
        $this->assertEquals('paid', $updated->fee_state);
    }

    /**
     * INVARIANT: Projection update is idempotent
     * Replaying the same FeePaid event multiple times must not corrupt state.
     */
    public function test_member_fee_state_is_idempotent_on_replay(): void
    {
        // Arrange
        $memberId = DomainIdFactory::member();
        $tenantId = DomainIdFactory::tenant();

        MemberContextModel::create([
            'id' => $memberId->value(),
            'organisation_id' => $tenantId->value(),
            'membership_type_id' => DomainIdFactory::membershipType()->value(),
            'status' => 'active',
            'fee_state' => 'unpaid',
            'personal_info' => json_encode([
                'fullName' => 'Idempotent Member',
                'email' => 'idempotent@example.com',
                'phone' => '+9999999999',
            ]),
        ]);

        $event = new FeePaid(
            feeId: DomainIdFactory::fee(),
            memberId: $memberId,
            tenantId: $tenantId,
            amount: '250.00',
            paymentMethod: 'direct_debit',
            paidAt: new DateTimeImmutable(),
            transactionReference: 'TX-IDEMPOTENT-001'
        );

        $listener = app(MemberFeeStateListener::class);

        // Act: deliver event multiple times (simulating outbox retry)
        $listener->handle($event);
        $listener->handle($event);
        $listener->handle($event);

        // Assert: state is consistent (not duplicated or corrupted)
        $updated = MemberContextModel::find($memberId->value());
        $this->assertEquals('paid', $updated->fee_state);

        // Verify database has only one member record (no duplication)
        $this->assertDatabaseCount('members', 1);
    }

    /**
     * INVARIANT: Listener does not require Fee aggregate reload
     * This ensures projection is independent of Fee repository (no circular dependencies).
     */
    public function test_member_listener_uses_only_event_context(): void
    {
        // Arrange: member exists
        $memberId = DomainIdFactory::member();
        $tenantId = DomainIdFactory::tenant();

        MemberContextModel::create([
            'id' => $memberId->value(),
            'organisation_id' => $tenantId->value(),
            'membership_type_id' => DomainIdFactory::membershipType()->value(),
            'status' => 'active',
            'fee_state' => 'unpaid',
            'personal_info' => json_encode([
                'fullName' => 'Independent Member',
                'email' => 'independent@example.com',
            ]),
        ]);

        // Note: no Fee record created (proves listener doesn't reload it)
        $event = new FeePaid(
            feeId: DomainIdFactory::fee(),
            memberId: $memberId,
            tenantId: $tenantId,
            amount: '75.00',
            paymentMethod: 'paypal',
            paidAt: new DateTimeImmutable()
        );

        $listener = app(MemberFeeStateListener::class);

        // Act: listener should succeed even though Fee doesn't exist in DB
        $listener->handle($event);

        // Assert: Member updated correctly
        $updated = MemberContextModel::find($memberId->value());
        $this->assertEquals('paid', $updated->fee_state);
    }

    /**
     * INVARIANT: Listener is safe against missing member
     * If member doesn't exist, listener gracefully returns (no cascade delete/corruption).
     */
    public function test_listener_handles_missing_member_gracefully(): void
    {
        // Arrange: event for non-existent member
        $event = new FeePaid(
            feeId: DomainIdFactory::fee(),
            memberId: DomainIdFactory::member(),
            tenantId: DomainIdFactory::tenant(),
            amount: '100.00',
            paymentMethod: 'bank_transfer',
            paidAt: new DateTimeImmutable()
        );

        $listener = app(MemberFeeStateListener::class);

        // Act: should not throw
        $listener->handle($event);

        // Assert: no member created, no error
        $this->assertDatabaseCount('members', 0);
    }

    /**
     * INVARIANT: Eventual consistency boundary is clear
     * Fee truth is in Fee aggregate; Member is derived read-model projection.
     * System remains consistent even if Fee and Member diverge temporarily.
     */
    public function test_member_projection_is_eventual_consistency_model(): void
    {
        // Arrange: member starts with unpaid state
        $memberId = DomainIdFactory::member();
        $tenantId = DomainIdFactory::tenant();

        MemberContextModel::create([
            'id' => $memberId->value(),
            'organisation_id' => $tenantId->value(),
            'membership_type_id' => DomainIdFactory::membershipType()->value(),
            'status' => 'active',
            'fee_state' => 'unpaid',
            'personal_info' => json_encode([
                'fullName' => 'Eventual Member',
                'email' => 'eventual@example.com',
            ]),
        ]);

        $this->assertEquals('unpaid', MemberContextModel::find($memberId->value())->fee_state);

        // Act: fee is paid (async event not yet processed)
        // At this moment: Fee aggregate knows PAID, but Member projection is still UNPAID
        // This is acceptable (eventual consistency)

        // Then outbox processor delivers the event
        $event = new FeePaid(
            feeId: DomainIdFactory::fee(),
            memberId: $memberId,
            tenantId: $tenantId,
            amount: '100.00',
            paymentMethod: 'bank_transfer',
            paidAt: new DateTimeImmutable()
        );

        $listener = app(MemberFeeStateListener::class);
        $listener->handle($event);

        // Assert: projection eventually becomes consistent
        $this->assertEquals('paid', MemberContextModel::find($memberId->value())->fee_state);
    }
}
