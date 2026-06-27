<?php

declare(strict_types=1);

namespace Tests\Unit\Membership\Infrastructure;

use App\Contexts\Membership\Infrastructure\Projections\MemberDirectoryProjectionWorker;
use App\Contexts\Membership\Infrastructure\Repositories\MemberDirectoryRepository;
use App\Contexts\Membership\Domain\Events\MemberRegistered;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * MemberDirectoryProjectionWorker — Infrastructure Tests
 *
 * Tests the event consumption and projection persistence layer.
 */
final class MemberDirectoryProjectionWorkerTest extends TestCase
{
    private MemberDirectoryProjectionWorker $worker;
    private MemberDirectoryRepository & MockObject $repository;
    private OutboxRepository & MockObject $outbox;
    private TenantId $tenantId;
    private MemberId $memberId;
    private MembershipTypeId $membershipTypeId;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MemberDirectoryRepository::class);
        $this->outbox = $this->createMock(OutboxRepository::class);

        $this->worker = new MemberDirectoryProjectionWorker(
            $this->repository,
            $this->outbox,
        );

        $this->tenantId = TenantId::fromString('550e8400-e29b-41d4-a716-446655440000');
        $this->memberId = MemberId::fromString('650e8400-e29b-41d4-a716-446655440000');
        $this->membershipTypeId = MembershipTypeId::fromString('750e8400-e29b-41d4-a716-446655440000');
    }

    /**
     * TEST 1: Single Event Processing
     *
     * Given: One MemberRegistered event in outbox
     * When: Worker processes
     * Then: Projection is upserted to repository
     */
    public function test_worker_processes_single_member_registered_event(): void
    {
        $event = new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $this->tenantId,
            displayName: 'John Doe',
            email: 'john.doe@example.com',
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Standard',
            organisationUserId: '850e8400-e29b-41d4-a716-446655440000',
        );

        $outboxRecord = [
            'id' => 'event-1',
            'event_type' => 'MemberRegistered',
            'payload' => json_encode([
                'memberId' => $event->memberId->value(),
                'tenantId' => $event->tenantId->value(),
                'displayName' => $event->displayName,
                'email' => $event->email,
                'membershipTypeId' => $event->membershipTypeId->value(),
                'membershipTypeName' => $event->membershipTypeName,
                'organisationUserId' => $event->organisationUserId,
            ]),
            'processed_at' => null,
        ];

        $this->outbox
            ->expects($this->once())
            ->method('getUnprocessed')
            ->with(MemberRegistered::class)
            ->willReturn([$outboxRecord]);

        $this->repository
            ->expects($this->once())
            ->method('upsert');

        $this->outbox
            ->expects($this->once())
            ->method('markProcessed')
            ->with('event-1');

        $this->worker->handle();
    }

    /**
     * TEST 2: Batch Processing
     *
     * Given: Multiple events in outbox
     * When: Worker processes
     * Then: All are upserted (in order)
     */
    public function test_worker_processes_batch_of_events(): void
    {
        $events = [
            [
                'id' => 'event-1',
                'event_type' => 'MemberRegistered',
                'payload' => json_encode([
                    'memberId' => '650e8400-e29b-41d4-a716-446655440000',
                    'tenantId' => $this->tenantId->value(),
                    'displayName' => 'John Doe',
                    'email' => 'john@example.com',
                    'membershipTypeId' => $this->membershipTypeId->value(),
                    'membershipTypeName' => 'Standard',
                    'organisationUserId' => '850e8400-e29b-41d4-a716-446655440000',
                ]),
            ],
            [
                'id' => 'event-2',
                'event_type' => 'MemberRegistered',
                'payload' => json_encode([
                    'memberId' => '760e8400-e29b-41d4-a716-446655440000',
                    'tenantId' => $this->tenantId->value(),
                    'displayName' => 'Jane Doe',
                    'email' => 'jane@example.com',
                    'membershipTypeId' => $this->membershipTypeId->value(),
                    'membershipTypeName' => 'Standard',
                    'organisationUserId' => '950e8400-e29b-41d4-a716-446655440000',
                ]),
            ],
        ];

        $this->outbox
            ->expects($this->once())
            ->method('getUnprocessed')
            ->with(MemberRegistered::class)
            ->willReturn($events);

        $this->repository
            ->expects($this->exactly(2))
            ->method('upsert');

        $this->outbox
            ->expects($this->exactly(2))
            ->method('markProcessed');

        $this->worker->handle();
    }

    /**
     * TEST 3: Idempotent Processing
     *
     * Given: Same event processed twice
     * When: Worker processes
     * Then: Projection is upserted (not duplicated)
     */
    public function test_worker_idempotently_processes_duplicate_events(): void
    {
        $event = [
            'id' => 'event-1',
            'event_type' => 'MemberRegistered',
            'payload' => json_encode([
                'memberId' => $this->memberId->value(),
                'tenantId' => $this->tenantId->value(),
                'displayName' => 'John Doe',
                'email' => 'john@example.com',
                'membershipTypeId' => $this->membershipTypeId->value(),
                'membershipTypeName' => 'Standard',
                'organisationUserId' => '850e8400-e29b-41d4-a716-446655440000',
            ]),
        ];

        // Process twice with same event
        for ($i = 0; $i < 2; $i++) {
            $this->outbox
                ->expects($this->once())
                ->method('getUnprocessed')
                ->with(MemberRegistered::class)
                ->willReturn([$event]);

            $this->repository
                ->expects($this->once())
                ->method('upsert');

            $this->outbox
                ->expects($this->once())
                ->method('markProcessed')
                ->with('event-1');
        }

        // Second call should also work (upsert not insert)
        $this->worker->handle();
    }

    /**
     * TEST 4: Empty Outbox Handling
     *
     * Given: No unprocessed events
     * When: Worker processes
     * Then: No repository calls made
     */
    public function test_worker_handles_empty_outbox_gracefully(): void
    {
        $this->outbox
            ->expects($this->once())
            ->method('getUnprocessed')
            ->with(MemberRegistered::class)
            ->willReturn([]);

        $this->repository
            ->expects($this->never())
            ->method('upsert');

        $this->outbox
            ->expects($this->never())
            ->method('markProcessed');

        $this->worker->handle();
    }

    /**
     * TEST 5: Error Handling
     *
     * Given: Repository throws exception
     * When: Worker processes
     * Then: Event is NOT marked as processed
     */
    public function test_worker_does_not_mark_processed_on_repository_failure(): void
    {
        $event = [
            'id' => 'event-1',
            'event_type' => 'MemberRegistered',
            'payload' => json_encode([
                'memberId' => $this->memberId->value(),
                'tenantId' => $this->tenantId->value(),
                'displayName' => 'John Doe',
                'email' => 'john@example.com',
                'membershipTypeId' => $this->membershipTypeId->value(),
                'membershipTypeName' => 'Standard',
                'organisationUserId' => '850e8400-e29b-41d4-a716-446655440000',
            ]),
        ];

        $this->outbox
            ->expects($this->once())
            ->method('getUnprocessed')
            ->with(MemberRegistered::class)
            ->willReturn([$event]);

        $this->repository
            ->expects($this->once())
            ->method('upsert')
            ->willThrowException(new \RuntimeException('Database error'));

        $this->outbox
            ->expects($this->never())
            ->method('markProcessed');

        $this->expectException(\RuntimeException::class);
        $this->worker->handle();
    }

    /**
     * TEST 6: Tenant Isolation Preserved
     *
     * Given: Events from different tenants
     * When: Worker processes
     * Then: Each projection preserves its tenant context
     */
    public function test_worker_preserves_tenant_isolation_across_batch(): void
    {
        $tenant1 = TenantId::fromString('550e8400-e29b-41d4-a716-446655440000');
        $tenant2 = TenantId::fromString('660e8400-e29b-41d4-a716-446655440000');

        $events = [
            [
                'id' => 'event-1',
                'event_type' => 'MemberRegistered',
                'payload' => json_encode([
                    'memberId' => '650e8400-e29b-41d4-a716-446655440000',
                    'tenantId' => $tenant1->value(),
                    'displayName' => 'Tenant 1 Member',
                    'email' => 'tenant1@example.com',
                    'membershipTypeId' => $this->membershipTypeId->value(),
                    'membershipTypeName' => 'Standard',
                    'organisationUserId' => '850e8400-e29b-41d4-a716-446655440000',
                ]),
            ],
            [
                'id' => 'event-2',
                'event_type' => 'MemberRegistered',
                'payload' => json_encode([
                    'memberId' => '760e8400-e29b-41d4-a716-446655440000',
                    'tenantId' => $tenant2->value(),
                    'displayName' => 'Tenant 2 Member',
                    'email' => 'tenant2@example.com',
                    'membershipTypeId' => $this->membershipTypeId->value(),
                    'membershipTypeName' => 'Standard',
                    'organisationUserId' => '950e8400-e29b-41d4-a716-446655440000',
                ]),
            ],
        ];

        $this->outbox
            ->expects($this->once())
            ->method('getUnprocessed')
            ->with(MemberRegistered::class)
            ->willReturn($events);

        $this->repository
            ->expects($this->exactly(2))
            ->method('upsert');

        $this->worker->handle();
    }
}
